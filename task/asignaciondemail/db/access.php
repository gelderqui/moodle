<?php

$capabilities = array(
   'local/geldercorreo:sendmail' => array(
        'riskbitmask' => RISK_DATALOSS,
        'captype'     => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'  => array(
            'manager'   => CAP_ALLOW,
             )
        ),
);
