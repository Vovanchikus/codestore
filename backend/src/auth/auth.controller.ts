import {
  Body,
  Controller,
  Post,
  Req,
  Res,
  UnauthorizedException,
  UseGuards,
} from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import type { Request, Response } from 'express';
import { AuthService } from './auth.service';
import { RegisterDto } from './dto/register.dto';
import { LoginDto } from './dto/login.dto';
import { JwtAuthGuard } from './guards/jwt-auth.guard';

@Controller('auth')
export class AuthController {
  constructor(
    private readonly authService: AuthService,
    private readonly jwtService: JwtService,
  ) {}

  private readonly refreshCookieOptions = {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'strict' as const,
  };

  @Post('register')
  async registerUser(@Body() dto: RegisterDto) {
    return this.authService.registerUser(dto);
  }

  @Post('login')
  async login(
    @Body() dto: LoginDto,
    @Res({ passthrough: true }) res: Response,
  ) {
    const tokens = await this.authService.loginUser(dto);
    res.cookie(
      'refresh_token',
      tokens.refresh_token,
      this.refreshCookieOptions,
    );
    return { access_token: tokens.access_token };
  }

  @Post('refresh')
  async refresh(
    @Req() req: Request,
    @Res({ passthrough: true }) res: Response,
  ) {
    const rt = req.cookies?.refresh_token;

    if (!rt) {
      throw new UnauthorizedException('Refresh token отсутствует.');
    }

    const payload = this.jwtService.verify<{ sub: number }>(rt);

    const tokens = await this.authService.refresh(payload.sub, rt);
    res.cookie(
      'refresh_token',
      tokens.refresh_token,
      this.refreshCookieOptions,
    );

    return { access_token: tokens.access_token };
  }

  @Post('logout')
  @UseGuards(JwtAuthGuard)
  logout(@Req() req: any, @Res({ passthrough: true }) res: Response) {
    res.clearCookie('refresh_token');
    return this.authService.logout(req.user.userId);
  }
}
