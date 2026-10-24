import app from 'flarum/admin/app';
import m from 'mithril';
import Extend from 'flarum/common/extenders';
import AutoPurgeSetting from './components/AutoPurgeSetting';
import trans from './helpers';

let actionBusy = false;

function requestErrorText(e: any): string {
  const detail = e?.response?.errors?.[0]?.detail;

  if (typeof detail === 'string' && detail !== '') return detail;

  const text = typeof e?.responseText === 'string' ? e.responseText : typeof e?.message === 'string' ? e.message : String(e);

  return text.slice(0, 200);
}

function runCloudflareAction(url: string, then: (d: any) => void, errorKey: string): void {
  if (actionBusy) return;

  actionBusy = true;

  app.request({ method: 'POST', url })
    .then((d: any) => {
      actionBusy = false;
      m.redraw();
      then(d);
    })
    .catch((e: any) => {
      actionBusy = false;
      m.redraw();
      app.alerts.show({ type: 'error' }, `${trans(errorKey)} ${requestErrorText(e)}`.trim());
    });
}

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
      setting: 'cloudflare.development_mode',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.development_mode_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.development_mode_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.bot_fight_mode',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.bot_fight_mode_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.bot_fight_mode_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.always_online',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.always_online_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.always_online_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.brotli',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.brotli_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.brotli_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.rocket_loader',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.rocket_loader_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.rocket_loader_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.mirage',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.mirage_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.mirage_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.minify_js',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_js_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_js_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.minify_css',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_css_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_css_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.minify_html',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_html_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.minify_html_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.security_level',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.security_level_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.security_level_help'),
      options: {
        off: 'Off',
        essentially_off: 'Essentially Off',
        low: 'Low',
        medium: 'Medium',
        high: 'High',
        under_attack: 'Under Attack',
      },
      default: 'medium',
    }))
    .setting(() => ({
      setting: 'cloudflare.polish',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.polish_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.polish_help'),
      options: {
        off: 'Off',
        lossless: 'Lossless',
        lossy: 'Lossy',
      },
      default: 'off',
    }))
    .setting(() => ({
      setting: 'cloudflare.browser_cache_ttl',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.browser_cache_ttl_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.browser_cache_ttl_help'),
      options: {
        '0': 'Respect Existing Headers',
        '30': '30 seconds',
        '60': '1 minute',
        '300': '5 minutes',
        '1200': '20 minutes',
        '1800': '30 minutes',
        '3600': '1 hour',
        '7200': '2 hours',
        '10800': '3 hours',
        '14400': '4 hours',
        '28800': '8 hours',
        '43200': '12 hours',
        '57600': '16 hours',
        '72000': '20 hours',
        '86400': '1 day',
        '172800': '2 days',
        '259200': '3 days',
        '345600': '4 days',
        '432000': '5 days',
        '691200': '8 days',
        '1382400': '16 days',
        '2073600': '24 days',
        '2678400': '1 month',
        '5356800': '2 months',
        '16070400': '6 months',
        '31536000': '1 year',
      },
      default: '14400',
    }))
    .setting(() => ({
      setting: 'cloudflare.ssl_mode',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.ssl_mode_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.ssl_mode_help'),
      options: {
        off: 'Off',
        flexible: 'Flexible',
        full: 'Full',
        strict: 'Full (Strict)',
      },
      default: 'flexible',
    }))
    .setting(() => ({
      setting: 'cloudflare.always_use_https',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.always_use_https_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.always_use_https_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.min_tls_version',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.min_tls_version_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.min_tls_version_help'),
      options: {
        '1.0': 'TLS 1.0',
        '1.1': 'TLS 1.1',
        '1.2': 'TLS 1.2',
        '1.3': 'TLS 1.3',
      },
      default: '1.2',
    }))
    .setting(() => ({
      setting: 'cloudflare.tls_1_3',
      type: 'select',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.tls_1_3_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.tls_1_3_help'),
      options: {
        off: 'Off',
        on: 'On',
        zrt: 'On + 0-RTT',
      },
      default: 'on',
    }))
    .setting(() => ({
      setting: 'cloudflare.automatic_https_rewrites',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.automatic_https_rewrites_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.automatic_https_rewrites_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.http2',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.http2_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.http2_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.http3',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.http3_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.http3_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.0rtt',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.0rtt_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.0rtt_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.early_hints',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.early_hints_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.early_hints_help'),
    }))
    .setting(() => ({
      setting: 'cloudflare.opportunistic_encryption',
      type: 'bool',
      label: app.translator.trans('cengizkemec-cloudflare.admin.settings.opportunistic_encryption_label'),
      help: app.translator.trans('cengizkemec-cloudflare.admin.settings.opportunistic_encryption_help'),
    }))
    .customSetting(function (this: any) {
      return m(AutoPurgeSetting, {
        stream: this.setting('cloudflare.auto_purge', '0'),
      });
    })
    .customSetting(() => m('div', { className: 'Form-group Cloudflare-actions' }, [
      m('label', { className: 'control-label' }, trans('actions_label')),
      m('div', { className: 'helpText' }, trans('actions_help')),
      m('div', { style: { display: 'flex', gap: '8px', marginTop: '8px', flexWrap: 'wrap' } }, [
        m('button', {
          type: 'button',
          className: 'Button',
          disabled: actionBusy,
          onclick: () => {
            runCloudflareAction(
              '/cloudflare/test',
              (d) => {
                if (d.success) {
                  app.alerts.show({ type: 'success' }, trans('test_success'));
                } else {
                  app.alerts.show({ type: 'error' }, `${trans('test_failed')} ${String(d.message ?? '')}`.trim());
                }
              },
              'test_failed'
            );
          },
        }, trans('test_connection')),
        m('button', {
          type: 'button',
          className: 'Button Button--primary',
          disabled: actionBusy,
          onclick: () => {
            runCloudflareAction(
              '/cloudflare/apply',
              (d) => {
                const failed = d.errors ? Object.keys(d.errors).length : 0;

                if (failed === 0) {
                  app.alerts.show({ type: 'success' }, trans('apply_success'));
                } else {
                  app.alerts.show({ type: 'error' }, `${trans('apply_partial')} (${failed}) ${String(d.message ?? '')}`.trim());
                }
              },
              'apply_failed'
            );
          },
        }, trans('apply_settings')),
        m('button', {
          type: 'button',
          className: 'Button',
          disabled: actionBusy,
          onclick: () => {
            runCloudflareAction(
              '/cloudflare/purge',
              (d) => {
                if (d.success) {
                  app.alerts.show({ type: 'success' }, trans('purge_success'));
                } else {
                  app.alerts.show({ type: 'error' }, `${trans('purge_failed')} ${String(d.message ?? '')}`.trim());
                }
              },
              'purge_failed'
            );
          },
        }, trans('purge_cache')),
      ]),
    ])),
];