<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_cmi'),
            get_string('descconfig', 'block_cmi')
        ));

$settings->add(new admin_setting_configcheckbox(
            'cmi/Allow_HTML',
            get_string('labelallowhtml', 'block_cmi'),
            get_string('descallowhtml', 'block_cmi'),
            '0'

        ));
