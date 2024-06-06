<?php
// každá webová stránka začíná prologem

// --- adresáře ---
const TITLE = "Migilog";
const TRANSFORM_SERVER_SIDE = true;

// --- konfigurace stránek ---

// --- session ---
session_start();  // ze všeho nejdříve začít seanci, pak používat $_SESSION

// jméno přihlášeného uživatele (s prefixem) nebo ''
function getJmeno($prefix = ''): string
{
    $jmeno = @$_SESSION['jmeno'];
    return $jmeno ? "$prefix$jmeno" : '';
}

// nastav nebo smaž jméno přihlášeného uživatele
function setJmeno($jmeno = ''): void
{
    if ($jmeno)
        $_SESSION['jmeno'] = $jmeno;
    else
        unset($_SESSION['jmeno']);
}

// Je přihlášen uživatel?
function isUser(): bool
{
    return !!getJmeno();
}