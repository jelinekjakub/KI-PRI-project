<?php // vypsat drinky:
require_once 'app/xml.php';
require_once 'app/database.php';

require 'app/prolog.php';
require 'components/header.php';
require 'components/nav.php';

use App\Xml;
use App\Database;
?>

<h1 class="py-6 text-center text-5xl"><a href="drinks.php">Drinky</a></h1>
<?php if (!isset($_GET['drink'])) { ?>
    <div class="bg-blue-gray-100 p-6 rounded-lg shadow-md w-3/4 mx-auto">
        <ol class="list-none">
            <?php foreach (Xml::listFiles('resources/drinks') as $basename) { ?>
                <li class="flex items-center mb-4">
                    <i class="fas fa-glass text-blue-500 mr-2"></i>
                    <a href="?drink=<?= $basename ?>" class="hover:underline text-blue-700"><?= $basename ?> (<?= Database::readDrink($basename) ?>)</a>
                </li>
            <?php } ?>
        </ol>
    </div>

<?php } ?>

<section class="flex justify-center bg-gray-50">
    <?php // zvolený drink:
    if ($drink = @$_GET['drink']) {
        if (TRANSFORM_SERVER_SIDE) { ?>

            <div id="recipe-container" class="p-8 mb-16 max-w-xl"></div>
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const drinkName = urlParams.get('drink');
                const filePath = `resources/drinks/${drinkName}.xml`;

                fetch(filePath)
                    .then(response => response.text())
                    .then(xmlString => {
                        const parser = new DOMParser();
                        const xmlDoc = parser.parseFromString(xmlString, "text/xml");

                        const drink = xmlDoc.getElementsByTagName("drink")[0];
                        const informace = drink.getElementsByTagName("informace")[0];
                        const ingredience = drink.getElementsByTagName("ingredience")[0];
                        const postup = drink.getElementsByTagName("postup")[0];

                        const author = drink.getAttribute("autor_článku") || '';
                        const rating = drink.getAttribute("hodnocení") || '';
                        const numRaters = drink.getAttribute("počet_hodnotících") || '';

                        const nazev = informace.getElementsByTagName("název")[0]?.textContent || '';
                        const zemePuvodu = [...informace.getElementsByTagName("země_původu")].map(zeme => zeme.textContent).join(', ') || '';
                        const dobaPripravy = informace.getElementsByTagName("doba_přípravy")[0]?.textContent || '';
                        const jednotkaDoba = informace.getElementsByTagName("doba_přípravy")[0]?.getAttribute("jednotka") || '';
                        const obtiznost = informace.getElementsByTagName("obtížnost")[0]?.firstElementChild?.tagName.toLowerCase() || '';

                        const ingredientList = [...ingredience.getElementsByTagName("položka")].map(polozka => {
                            const typ = polozka.getAttribute("typ") || '';
                            const mnozstvi = polozka.getAttribute("množství") || '';
                            const jednotka = polozka.getAttribute("jednotka") || '';
                            const nazev = polozka.textContent || '';
                            return { typ, mnozstvi, jednotka, nazev };
                        });

                        const postupText = postup ? postup.textContent : '';

                        // Creating HTML content
                        const recipeContainer = document.getElementById('recipe-container');
                        if (recipeContainer) {
                            recipeContainer.innerHTML = `
                <div class="bg-white shadow-md rounded-lg p-6">
                  <h1 class="text-2xl font-bold mb-4">${nazev}</h1>
                  <p class="text-gray-700"><strong>Autor:</strong> ${author}</p>
                  <p class="text-gray-700"><strong>Hodnocení:</strong> ${rating} (${numRaters} recenzí)</p>
                  <p class="text-gray-700"><strong>Země původu:</strong> ${zemePuvodu}</p>
                  <p class="text-gray-700"><strong>Čas přípravy:</strong> ${dobaPripravy} ${jednotkaDoba}</p>
                  <p class="text-gray-700"><strong>Obtížnost:</strong> ${obtiznost}</p>
                  <h2 class="text-xl font-semibold mt-6 mb-2">Ingredience</h2>
                  <ul class="flex flex-col gap-4">
                    ${ingredientList.map(ingredient => `
                      <label for="${ingredient.nazev}"><li class="border p-2 rounded-lg cursor-pointer hover:bg-slate-100"><input type="checkbox" name="${ingredient.nazev}" id="${ingredient.nazev}">${ingredient.nazev} - ${ingredient.mnozstvi} ${ingredient.jednotka} (${ingredient.typ})</li></label>
                    `).join('')}
                  </ul>
                  <h2 class="text-xl font-semibold mt-6 mb-2">Postup</h2>
                  <p class="text-gray-700">${postupText}</p>
                </div>`;
                        } else {
                            console.error('Recipe container not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching or parsing XML:', error);
                    });
            });
        </script>

        <?php } else { ?>
            <h2 id="nazev" class="text-center text-2xl m-4">
            <script>
                loadXML(
                    "/app/getDrink.php?drink=<?= $drink ?>",
                    (xmlDom) => {
                        // zde je možné pracovat s DOM ...
                        document.getElementById("nazev").innerHTML =
                            xmlDom.getElementsByTagName("název")[0].textContent;
                        // ... atd.
                    })
            </script>
        <?php }
    } ?>
</section>

<?php require 'components/footer.php';
