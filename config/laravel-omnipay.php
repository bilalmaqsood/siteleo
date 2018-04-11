<?php

return [

	// The default gateway to use
	'default' => 'Redsys',

	// Add in each gateway here
	'gateways' => [
		'paypal' => [
			'driver'  => 'PayPal_Express',
			'options' => [
				'solutionType'   => '',
				'landingPage'    => '',
				'headerImageUrl' => ''
			]
		],
                'Redsys' => [
                    'driver' => 'Redsys',
                    'options' => [
                        'merchantCode' => '999008881',
                        'secretKey' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
                        'terminal' => 1,
                        'testMode' => true,
                    ]
                ]
	]

];