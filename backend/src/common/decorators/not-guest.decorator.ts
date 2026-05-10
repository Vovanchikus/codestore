import { SetMetadata } from '@nestjs/common';

export const NOT_GUEST_KEY = 'not_guest';
export const NotGuest = () => SetMetadata(NOT_GUEST_KEY, true);
