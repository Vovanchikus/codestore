<!-- Textarea -->
<?php if ($this->previewMode): ?>
    <?php
        $val = $field->value;
        if (is_array($val)) {
            $flatten = function($v) use (&$flatten) {
                if (is_array($v)) {
                    $parts = [];
                    foreach ($v as $item) {
                        $parts[] = $flatten($item);
                    }
                    return implode("\n", $parts);
                }
                return strval($v);
            };
            $val = $flatten($val);
        }
    ?>
    <div class="form-control"><?= nl2br(e($val)) ?></div>
<?php else: ?>
    <?php
        $val = $field->value;
        if (is_array($val)) {
            $flatten = function($v) use (&$flatten) {
                if (is_array($v)) {
                    $parts = [];
                    foreach ($v as $item) {
                        $parts[] = $flatten($item);
                    }
                    return implode("\n", $parts);
                }
                return strval($v);
            };
            $val = $flatten($val);
        }
    ?>
    <textarea
        name="<?= $field->getName() ?>"
        id="<?= $field->getId() ?>"
        autocomplete="off"
        class="form-control field-textarea size-<?= $field->size ?>"
        placeholder="<?= e(trans($field->placeholder)) ?>"
        <?= $field->getAttributes() ?>><?= e($val) ?></textarea>
<?php endif?>
