<?php


namespace App\Data;


class SearchData
{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';


    /**
     * var Couleur[]
     */

    public $couleurs = [];

    /**
     * var Tailles[]
     */

    public $tailles = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;


}