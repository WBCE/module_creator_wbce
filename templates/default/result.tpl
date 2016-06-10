<div class="mod_module_creator">
    <h1><?php echo $MOD_MC_TEXT['MOD_CREATED'] ?>: <?php echo $admin->get_post('new_modulename') ?></h1>
    <table>
    <?php foreach(array_values($result) as $line): echo $line; endforeach; ?>
    </table>
</div>