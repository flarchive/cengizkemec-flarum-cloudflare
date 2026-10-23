import app from 'flarum/admin/app';
import Extend from 'flarum/common/extenders';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'cloudflare.enabled',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.enabled_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.enabled_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.api_token',
      type: 'password',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.api_token_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.api_token_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.account_id',
      type: 'text',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.account_id_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.account_id_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.zone_id',
      type: 'text',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.zone_id_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.zone_id_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.zone_name',
      type: 'text',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.zone_name_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.zone_name_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.auto_purge',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.auto_purge_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.auto_purge_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.development_mode',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.development_mode_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.development_mode_help'),
    })),
];