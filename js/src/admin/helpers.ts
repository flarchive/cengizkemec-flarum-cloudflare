import app from 'flarum/admin/app';

export default function trans(key: string, params?: Record<string, unknown>): string {
  return String(app.translator.trans(`cengizkemec-cloudflare.admin.settings.${key}`, params));
}
