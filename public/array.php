<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$array = [
    'sequence' => [
        'q_1' => [
            'qid' => 1,
            'option_1' => [
                [
                    'aid' => 'a',
                    'sequence' =>
                    [
                        'q_2' => 2,
                        
                    ]
                ],
                [
                    'aid' => 'b',
                    'sequence' =>
                    [
                        'q_2' => 2,
                        
                    ]
                ]
            ]
        ]
    ]
];
var_dump($array);
