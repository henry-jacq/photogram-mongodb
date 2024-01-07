<?php

namespace App\Interfaces;

interface DatabaseConnectorInterface
{
    public function getConnection();
    public function query($query, $params = []);
    public function execute($query, $params = []);
}