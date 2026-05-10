<div data-control="toolbar">
    <?php foreach ($relationToolbarButtons as $type => $text): ?>

        <?php if ($type === 'update'): ?>
            <?= $this->relationMakePartial('button_update', [
                'relationManageId' => $relationViewModel->getKey(),
                'text' => $text
            ]) ?>
        <?php else: ?>
            <?= $this->relationMakePartial('button_' . $type, [
                'text' => $text
            ]) ?>
        <?php endif ?>

    <?php endforeach ?>

    <div class="btn-group">
        <button
            class="btn btn-sm btn-secondary wn-icon-ban"
            onclick="$(this).data('request-data', (function(){
                    _relation_field: '<?= $relationField ?>',
                    _relation_extra_config: '<?= e(base64_encode(json_encode($relationExtraConfig))) ?>',
                    _session_key: '<?= $relationSessionKey ?>',
                    checked: $('#<?= $this->relationGetId('view') ?> .control-list').listWidget('getChecked')
                })"
            disabled="disabled"
            data-request="onRelationButtonDisableFields"
            data-request-success="$.wn.relationBehavior.changed('fields', 'disabled')"
            data-trigger-action="enable"
            data-trigger="#<?= $this->relationGetId('view') ?> .control-list input[type=checkbox]"
            data-trigger-condition="checked"
            data-stripe-load-indicator>
            <?= e('Отключить') ?>
        </button>

        <button
            class="btn btn-sm btn-secondary wn-icon-check"
            onclick="$(this).data('request-data', (function(){
                    _relation_field: '<?= $relationField ?>',
                    _relation_extra_config: '<?= e(base64_encode(json_encode($relationExtraConfig))) ?>',
                    _session_key: '<?= $relationSessionKey ?>',
                    checked: $('#<?= $this->relationGetId('view') ?> .control-list').listWidget('getChecked')
                })"
            disabled="disabled"
            data-request="onRelationButtonEnableFields"
            data-request-success="$.wn.relationBehavior.changed('fields', 'enabled')"
            data-trigger-action="enable"
            data-trigger="#<?= $this->relationGetId('view') ?> .control-list input[type=checkbox]"
            data-trigger-condition="checked"
            data-stripe-load-indicator>
            <?= e('Включить') ?>
        </button>
    </div>
</div>
