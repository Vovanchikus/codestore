import {
  BadRequestException,
  ConflictException,
  Injectable,
  UnauthorizedException,
} from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { PrismaService } from '../prisma/prisma.service';
import { RegisterDto } from './dto/register.dto';
import * as bcrypt from 'bcrypt';
import { LoginDto } from './dto/login.dto';

@Injectable()
export class AuthService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly jwtService: JwtService,
  ) {}

  async registerUser(dto: RegisterDto) {
    if (!dto) {
      throw new BadRequestException(
        'Тело запроса обязательно. Передайте name, email и password в JSON body.',
      );
    }

    const { name, email, password } = dto;

    if (!name || !email || !password) {
      throw new BadRequestException('Поля name, email и password обязательны.');
    }

    const existingEmail = await this.prisma.user.findUnique({
      where: { email },
    });

    const existingName = await this.prisma.user.findFirst({
      where: { name },
    });

    if (existingEmail) {
      throw new ConflictException('Пользователь с таким email уже существует');
    }

    if (existingName) {
      throw new ConflictException('Пользователь с таким именем уже существует');
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const user = await this.prisma.user.create({
      data: {
        name,
        email,
        password: hashedPassword,
      },
    });

    const { password: _password, ...result } = user;
    return result;
  }

  async loginUser(dto: LoginDto) {
    if (!dto) {
      throw new BadRequestException(
        'Тело запроса обязательно. Передайте name и password в JSON body.',
      );
    }

    const { name, password } = dto;

    if (!name || !password) {
      throw new UnauthorizedException('Поля name и password обязательны.');
    }

    const user = await this.prisma.user.findFirst({
      where: { name },
    });

    if (!user) {
      throw new UnauthorizedException('Пользователь с таким именем не найден.');
    }

    const isPasswordValid = await bcrypt.compare(password, user.password);

    if (!isPasswordValid) {
      throw new UnauthorizedException('Неверный пароль.');
    }

    if (!user.name) {
      throw new UnauthorizedException('У пользователя отсутствует имя.');
    }

    const tokens = await this.getTokens(user.id, user.name, user.role);

    await this.updateRefreshToken(user.id, tokens.refresh_token);

    return tokens;
  }

  async getTokens(userId: number, name: string, role: string) {
    const payload = { sub: userId, name, role };

    const [access_token, refresh_token] = await Promise.all([
      this.jwtService.signAsync(payload, { expiresIn: '15m' }),
      this.jwtService.signAsync(payload, { expiresIn: '7d' }),
    ]);

    return {
      access_token,
      refresh_token,
    };
  }

  async updateRefreshToken(userId: number, refreshToken: string) {
    const hash = await bcrypt.hash(refreshToken, 10);
    await this.prisma.user.update({
      where: { id: userId },
      data: { refreshToken: hash },
    });
  }

  async refresh(userId: number, refreshToken: string) {
    const user = await this.prisma.user.findUnique({
      where: { id: userId },
    });

    if (!user || !user.refreshToken) {
      throw new UnauthorizedException(
        'Пользователь не найден или токен обновления отсутствует.',
      );
    }

    const isMatch = await bcrypt.compare(refreshToken, user.refreshToken);

    if (!isMatch) {
      throw new UnauthorizedException('Неверный токен обновления.');
    }

    if (!user.name) {
      throw new UnauthorizedException('У пользователя отсутствует имя.');
    }

    const tokens = await this.getTokens(user.id, user.name, user.role);
    await this.updateRefreshToken(user.id, tokens.refresh_token);
    return tokens;
  }

  async logout(userId: number) {
    return this.prisma.user.update({
      where: { id: userId },
      data: { refreshToken: null }, // инвалидируем refresh_token
    });
  }
}
