export interface AuthUser {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'seller' | 'simple_buyer' | string;
  email_verified_at?: string | null;
}

export interface FlashProps {
  success?: string | null;
  error?: string | null;
  status?: string | null;
}

export interface SharedPageProps {
  auth: {
    user: AuthUser | null;
  };
  flash: FlashProps;
  errors: Record<string, string>;
  locale: string;
}
