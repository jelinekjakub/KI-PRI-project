# Co bylo přidáno, vylepšeno nebo odstraněno

- Byl změněn celkový přístup, z důvodu zdrojů a jednoduchosti byl projekt předělán na lokální server Apache
- Struktura projektu byla předělána z důvodu lepší orientace a přehledu
- Pro jednotlivé funkce, které byli zařazeny v jednotlivých souborech byl vytvořen komplexnejší přístup pomocí tříd
- Nově vznikla složka `app`, obsahující třídy nezbytné pro běh (`Auth`, `Database`, `Xml`, `Navigation`)
- Jednotlivé komponenty byli upraveny a přesunuty do složky `components`
- Soubory xml,xsd,xsl byli přesunuty do `resources/xml`
- Soubory s drinky do složky `resources/drinks`
- Nová stránka byla přidána, která umožňuje vytvořit uplně nový recept bez nahrávání `.xml` souborů
- Pomocí formuláře lze všechny parametry vyplnit, a při odeslání a `POST` requestu se vytvoří validní soubor, který se nahraje do příslušného adresáře
# Seminární práce: full-stack projekt

Tento ukázkový projekt obsahuje části typické webové aplikace:

- server (back-end): PHP, XML, databáze – běžící na Linuxovém serveru
- klient (front-end): HTML, XML, CSS, JavaScript – běžící v prohlížeči

Stránky používají šablonu a styly:

- responsivní šablonu upravenou z [codewithFaraz](https://www.codewithfaraz.com/content/229/how-to-create-a-simple-navbar-with-tailwind-css)
- CSS framework [tailwindcss](https://tailwindcss.com/)
- ikony z [Font Awesome](https://fontawesome.com/)

## Váš úkol (seminární práce) je:

1. Projekt si prostudujte.
1. Zvolte si, zda:
   - vytvoříte svůj vlastní, podobný projekt, nebo
   - ukázkový projekt zdokonalíte.
1. Svůj projekt realizujte a (fungující 🙂) přineste k zápočtu.

## Váš projekt by měl obsahovat následující:

- Zcela určitě: XML, XSD, XSL, XPath.
- Zcela určitě: číst data z XML (XSD, XSL) souborů, XML soubory (data přenášená mezi klientem a serverem) validovat a transformovat.
- Nahrávat (tj. zapisovat) validovaná data do XML souborů.
- Nepovinně – tedy pokud chcete – můžete číst a nahrávat data z/do databáze.
- Projekt by měl mít několik (3+) webových stránek, ostylovaných (je na vás, jestli ke stylování použijete obyčejné CSS, nebo některou z knihoven, jako Bootstrap, Tailwind, a podobně).
- Na serveru použít PHP, v klientu JavaScript.
- JavaScript by měl pracovat s HTML DOM (případně také XML DOM) a obsluhovat nějaké události (events), např. klikání a pohyb myši.

## Náměty na zdokonalení ukázkového projektu:

Pokud budete zdokonalovat ukázkový projekt, můžete například:

- Vytvořit stránku (formulář) na vyhledání a výpis drinků podle: obtížnosti, přísad, země původu, autora ...
- Zobrazit tabulku nejpopulárnějších drinků.
- Pomocí JavaScriptu (manipulace DOMu a obsluhy událostí) stránky „oživit“.
