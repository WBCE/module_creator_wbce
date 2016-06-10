<div class="mod_module_creator">
    <div class="info">
        <?php echo $MOD_MC_TEXT['MOD_INFO'] ?>
    </div>
    <form method="post" action="">
        <?php echo $admin->getFTAN(); ?>
        <input type="hidden" name="action" value="save" />
        <fieldset>
            <legend><?php echo $MOD_MC_TEXT['MOD_LEGEND'] ?></legend>
            <label for="new_moduletype"><?php echo $MOD_MC_TEXT['MOD_TYPE'] ?></label>
                <select id="new_moduletype" name="new_moduletype">
                    <option value="page"<?php if($data['new_moduletype'] == 'page'): echo ' selected="selected"'; endif; ?>><?php echo $MOD_MC_TEXT['MOD_TYPES']['PAGE'] ?></option>
                    <option value="tool"<?php if($data['new_moduletype'] == 'tool'): echo ' selected="selected"'; endif; ?>><?php echo $MOD_MC_TEXT['MOD_TYPES']['TOOL'] ?></option>
                    <option value="template"<?php if($data['new_moduletype'] == 'template'): echo ' selected="selected"'; endif; ?>><?php echo $MOD_MC_TEXT['MOD_TYPES']['TPL'] ?></option>
                    <option value="language"<?php if($data['new_moduletype'] == 'language'): echo ' selected="selected"'; endif; ?>><?php echo $MOD_MC_TEXT['MOD_TYPES']['LANG'] ?></option>
                </select><br />
            <label for="new_modulename"><?php echo $MOD_MC_TEXT['MOD_NAME'] ?></label>
                <input type="text" name="new_modulename" id="new_modulename" required="required" value="<?php echo $data['new_modulename'] ?>" /><br />
            <label for="new_moduledir"><?php echo $MOD_MC_TEXT['MOD_DIR'] ?></label>
                <input type="text" name="new_moduledir" id="new_moduledir" placeholder="<?php echo $MOD_MC_TEXT['MOD_DIR_INFO'] ?>" required="required" value="<?php echo $data['new_moduledir'] ?>" /><br />
            <label for="new_moduledesc"><?php echo $MOD_MC_TEXT['MOD_DESC'] ?></label>
                <textarea name="new_moduledesc" id="new_moduledesc" placeholder="<?php echo $MOD_MC_TEXT['MOD_DESC_INFO'] ?>" required="required" /><?php echo $data['new_moduledesc'] ?></textarea><br />
            <label for="new_moduleauthor"><?php echo $MOD_MC_TEXT['MOD_AUTH'] ?></label>
                <input type="text" value="<?php echo $username ?>" name="new_moduleauthor" id="new_moduleauthor" required="required" value="<?php echo $data['new_moduleauthor'] ?>" /><br />
            <label for="new_modulecopyright"><?php echo $MOD_MC_TEXT['MOD_COPY'] ?></label>
                <input type="text" value="(c) <?php echo date('Y') ?> <?php echo $username ?>" name="new_modulecopyright" id="new_modulecopyright" required="required" value="<?php echo $data['new_modulecopyright'] ?>" /><br />
            <label for="new_modulelicense"><?php echo $MOD_MC_TEXT['MOD_LIC'] ?></label>
                <select id="new_modulelicense" name="new_modulelicense" required="required">
                <?php foreach($licenses as $item => $url): ?>
                    <option value="<?php echo $item ?>"<?php if($data['new_modulelicense'] == $item): echo ' selected="selected"'; endif; ?>><?php echo $item ?></option>
                <?php endforeach; ?>
                </select><br />

            <input type="submit" value="<?php echo $MOD_MC_TEXT['MOD_SUBMIT'] ?>" />
            <input type="reset" value="Zurücksetzen" name="reset" />
        </fieldset>
    </form>
</div>