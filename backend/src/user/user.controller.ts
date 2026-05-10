import { Controller, Get, Param, Req, UseGuards } from '@nestjs/common';
import { UserService } from './user.service';
import { OptionalJwtAuthGuard } from '../auth/guards/optional-jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { NotGuest } from '../common/decorators/not-guest.decorator';
import { Public } from '../common/decorators/public.decorator';

@UseGuards(OptionalJwtAuthGuard, RolesGuard)
@Controller('users')
export class UserController {
  constructor(private userService: UserService) {}

  @NotGuest()
  @Get('me')
  getMe(@Req() req: any) {
    return this.userService.getMe(req.user.userId);
  }

  @Get(':id')
  getById(@Param('id') id: string) {
    return this.userService.getUserById(+id);
  }

  @NotGuest()
  @Get()
  getAllusers() {
    return this.userService.getAllUsers();
  }
}
