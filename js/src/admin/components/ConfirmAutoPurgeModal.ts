import Modal, { IInternalModalAttrs } from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Switch from 'flarum/common/components/Switch';
import type Mithril from 'mithril';
import trans from '../helpers';

export interface ConfirmAutoPurgeAttrs extends IInternalModalAttrs {
  onConfirm?: () => void;
}

export default class ConfirmAutoPurgeModal extends Modal<ConfirmAutoPurgeAttrs> {
  acknowledged: boolean = false;

  className(): string {
    return 'ConfirmAutoPurgeModal Modal--small';
  }

  title(): Mithril.Children {
    return trans('auto_purge_confirm_title');
  }

  content(): Mithril.Children {
    return m('.Modal-body', [
      m('div', { className: 'Form-group' }, [
        m('div', { className: 'helpText AutoPurgeConfirm-text' }, trans('auto_purge_confirm_text')),
        m('ul', { className: 'AutoPurgeConfirm-list' }, [
          m('li', trans('auto_purge_consequence_cache')),
          m('li', trans('auto_purge_consequence_speed')),
          m('li', trans('auto_purge_consequence_recommend')),
        ]),
        m(Switch, {
          state: this.acknowledged,
          onchange: (checked: boolean) => {
            this.acknowledged = checked;
          },
        }, trans('auto_purge_confirm_ack')),
        m('div', { className: 'Form-group Form-controls' }, [
          m(Button, {
            className: 'Button',
            onclick: () => this.hide(),
          }, trans('auto_purge_confirm_cancel')),
          m(Button, {
            className: 'Button Button--danger',
            disabled: !this.acknowledged,
            onclick: () => {
              this.hide();
              this.attrs.onConfirm?.();
            },
          }, trans('auto_purge_confirm_enable')),
        ]),
      ]),
    ]);
  }
}
