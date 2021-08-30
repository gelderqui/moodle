<?php

$capabilities = array(
   'local/geldercohortcl:synccohorts' => array(
        'riskbitmask' => RISK_DATALOSS,
        'captype'     => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'  => array(
            'manager'   => CAP_ALLOW,
             )
        ),
);
