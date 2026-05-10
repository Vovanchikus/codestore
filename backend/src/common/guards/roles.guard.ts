import {
  CanActivate,
  ExecutionContext,
  ForbiddenException,
  Injectable,
  UnauthorizedException,
} from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { Role } from '../enums/role.enum';
import { ROLES_KEY } from '../decorators/roles.decorator';
import { NOT_GUEST_KEY } from '../decorators/not-guest.decorator';
import { IS_PUBLIC_KEY } from '../decorators/public.decorator';

/*
  Пример использования в контроллере:
  1. @Roles(Role.ADMIN) - только для администраторов
  2. @Roles(Role.ADMIN, Role.MODER) - для администраторов и модераторов
  3. @NotGuest() - для всех, кроме гостей
  4. @Public() - для всех, включая гостей
  ** Если у метода нет декоратора @Roles и @NotGuest, то он доступен всем, включая гостей.
*/

@Injectable()
export class RolesGuard implements CanActivate {
  constructor(private reflector: Reflector) {}
  canActivate(context: ExecutionContext): boolean {
    // Проверяем, есть ли у метода декоратор @Public, если да - разрешаем доступ всем
    const isPublic = this.reflector.getAllAndOverride<boolean>(IS_PUBLIC_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);

    if (isPublic) return true;

    // Получаем роли, указанные в декораторе @Roles, и флаг из декоратора @NotGuest
    const requiredRoles = this.reflector.getAllAndOverride<Role[]>(ROLES_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);

    // Если нет декоратора @Roles и @NotGuest, то разрешаем доступ всем
    const notGuest = this.reflector.getAllAndOverride<boolean>(NOT_GUEST_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);

    const { user } = context.switchToHttp().getRequest();
    const role = user?.role || Role.GUEST;

    // Если указано, что доступ запрещен для гостей, и роль пользователя - гость, то запрещаем доступ
    if (notGuest && !user) {
      throw new UnauthorizedException('Требуется авторизация');
    }

    if (notGuest && role === Role.GUEST) {
      throw new ForbiddenException('Доступ запрещен для гостей');
    }

    // Если указаны роли, то проверяем, есть ли роль пользователя в списке разрешенных ролей
    if (requiredRoles && !user) {
      throw new UnauthorizedException('Требуется авторизация');
    }

    if (requiredRoles && !requiredRoles.includes(role)) {
      throw new ForbiddenException(
        'Недостаточно прав для доступа к этому ресурсу',
      );
    }

    return true;
  }
}
