import app from 'flarum/admin/app';
import Component from 'flarum/common/Component';
import Switch from 'flarum/common/components/Switch';
import Stream from 'flarum/common/utils/Stream';
import type Mithril from 'mithril';
import ConfirmAutoPurgeModal from './ConfirmAutoPurgeModal';
import trans from '../helpers';

export interface AutoPurgeSettingAttrs {
  stream: Stream<string | boolean>;
}

export default class AutoPurgeSetting extends Component<AutoPurgeSettingAttrs> {
  view(vnode: Mithril.Vnode<AutoPurgeSettingAttrs, this>): Mithril.Children {
    const value = this.attrs.stream();
    const enabled = !!value && value !== '0';

    return m('div', { className: 'Form-group AutoPurgeSetting' }, [
      m(Switch, {
        state: enabled,
        onchange: (checked: boolean) => this.onToggle(checked),
      }, trans('auto_purge_label')),
      m('div', { className: 'helpText' }, trans('auto_purge_help')),
      m('div', { className: 'Alert Alert--error AutoPurgeSetting-warning' }, [
        m('span', { className: 'Alert-body' }, trans('auto_purge_warning')),
      ]),
      enabled && m('div', { className: 'Alert Alert--error AutoPurgeSetting-active' }, [
        m('span', { className: 'Alert-body' }, trans('auto_purge_active')),
      ]),
    ]);
  }

  onToggle(checked: boolean): void {
    if (!checked) {
      this.attrs.stream(checked);
      return;
    }

    app.modal.show(ConfirmAutoPurgeModal, {
      onConfirm: () => {
        this.attrs.stream(true);
        m.redraw();
      },
    });
  }
}
