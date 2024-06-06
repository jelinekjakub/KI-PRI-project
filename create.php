<?php
require_once 'app/xml.php';

require 'app/prolog.php';
require 'components/header.php';
require 'components/nav.php';
require 'components/boxes.php';

use App\Xml;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nazev = $_POST['název'];
    if (Xml::validateName($nazev)) {
        $zemePuvodu = $_POST['země_původu'];
        $dobaPripravy = $_POST['doba_přípravy'];
        $jednotkaDobaPripravy = $_POST['jednotka_doba_přípravy'];
        $obtiznost = $_POST['obtížnost'];
        $autorClanku = $_POST['autor_článku'];
        $pocetPorci = $_POST['počet_porcí'];
        $postup = $_POST['postup'];

        $polozky = $_POST['položka'];
        if (empty($polozky))
        {
            errorBox("Co je to za recept bez ingrediencí?");
        }
        else
        {
            $jednotky = $_POST['jednotka'];
            $mnozstvi = $_POST['množství'];
            $odkazy = $_POST['odkaz_koupě'];
            $typy = $_POST['typ'];

            // Create a new XML document
            $xml = new DOMDocument("1.0", "UTF-8");
            $xml->formatOutput = true;

            // Create root element
            $root = $xml->createElement("drink");
            $root->setAttribute("autor_článku", $autorClanku);
            $root->setAttribute("hodnocení", "1");
            $root->setAttribute("počet_hodnotících", "0");
            $root->setAttribute("počet_porcí", $pocetPorci);
            $xml->appendChild($root);

            // Add informace element
            $informace = $xml->createElement("informace");
            $root->appendChild($informace);

            // Add nazev element
            $nazevElement = $xml->createElement("název", htmlspecialchars($nazev));
            $informace->appendChild($nazevElement);

            // Add země_původu elements
            foreach ($zemePuvodu as $zeme) {
                $zemeElement = $xml->createElement("země_původu", htmlspecialchars($zeme));
                $informace->appendChild($zemeElement);
            }

            // Add doba_přípravy element
            $dobaPripravyElement = $xml->createElement("doba_přípravy", htmlspecialchars($dobaPripravy));
            $dobaPripravyElement->setAttribute("jednotka", htmlspecialchars($jednotkaDobaPripravy));
            $informace->appendChild($dobaPripravyElement);

            // Add obtížnost element
            if (!empty($obtiznost)) {
                $obtiznostElement = $xml->createElement("obtížnost");
                $obtiznostElement->appendChild($xml->createElement($obtiznost));
                $informace->appendChild($obtiznostElement);
            }

            // Add ingredience element
            $ingredience = $xml->createElement("ingredience");
            $root->appendChild($ingredience);

            // Add položka elements
            for ($i = 0; $i < count($polozky); $i++) {
                $polozka = $xml->createElement("položka", htmlspecialchars($polozky[$i]));
                $polozka->setAttribute("odkaz_koupě", htmlspecialchars($odkazy[$i]));
                $polozka->setAttribute("typ", htmlspecialchars($typy[$i]));
                $polozka->setAttribute("jednotka", htmlspecialchars($jednotky[$i]));
                $polozka->setAttribute("množství", htmlspecialchars($mnozstvi[$i]));
                $ingredience->appendChild($polozka);
            }

            // Add postup element
            $postupElement = $xml->createElement("postup", htmlspecialchars($postup));
            $root->appendChild($postupElement);

            // Save XML to file
            $xml->save("resources/drinks/" . $nazev . ".xml");

            successBox("OK - $nazev - vytvořen");
        }
    }
    else
    {
        errorBox("Recept již v naší databázi máme.");
    }
}


?>
<script>
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const ingredientDiv = document.createElement('div');
        ingredientDiv.className = 'ingredient-item grid grid-cols-2 gap-4 mb-2 bg-gray-100 rounded-md';
        ingredientDiv.innerHTML = `
                <div class="col-span-2 sm:col-span-1">
                    <input type="text" name="položka[]" required placeholder="Ingredience" class="ingredient-name block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                <div class="hidden-fields col-span-2 sm:col-span-1 grid grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="jednotka[]" placeholder="Jednotka" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div>
                        <input type="number" step="0.1" name="množství[]" placeholder="Množství" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div>
                        <input type="text" name="odkaz_koupě[]" placeholder="Odkaz koupě" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div>
                        <select name="typ[]" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="základ">Základ</option>
                            <option value="dochucovadlo">Dochucovadlo</option>
                            <option value="dekorace">Dekorace</option>
                            <option value="nezařazené">Nezařazené</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-2 sm:col-span-1 flex items-center space-x-2">
                    <button type="button" onclick="markAsDone(this)" class="done-button inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Hotovo</button>
                    <button type="button" onclick="editIngredient(this)" class="edit-button hidden inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Upravit</button>
                    <button type="button" onclick="removeIngredient(this)" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Odstranit</button>
                </div>
            `;
        container.appendChild(ingredientDiv);
    }

    function removeIngredient(button) {
        button.parentElement.parentElement.remove();
    }

    function markAsDone(button) {
        const ingredientItem = button.parentElement.parentElement;
        const hiddenFields = ingredientItem.querySelector('.hidden-fields');
        hiddenFields.classList.add('hidden');
        button.classList.add('hidden');
        ingredientItem.querySelector('.edit-button').classList.remove('hidden');
    }

    function editIngredient(button) {
        const ingredientItem = button.parentElement.parentElement;
        const hiddenFields = ingredientItem.querySelector('.hidden-fields');
        hiddenFields.classList.remove('hidden');
        button.classList.add('hidden');
        ingredientItem.querySelector('.done-button').classList.remove('hidden');
    }
</script>
<div class="bg-gray-100 py-16">
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form class="space-y-6 grid grid-cols-1 sm:grid-cols-2 gap-6" method="post" action="create.php">
            <!-- název -->
            <div class="sm:col-span-2">
                <label for="název" class="block text-sm font-medium leading-6 text-gray-900">Název</label>
                <div class="mt-2">
                    <input type="text" name="název" id="název" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- země původu -->
            <div>
                <label for="země_původu" class="block text-sm font-medium leading-6 text-gray-900">Země původu</label>
                <div class="mt-2">
                    <input type="text" name="země_původu" id="země_původu" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- doba přípravy -->
            <div>
                <label for="doba_přípravy" class="block text-sm font-medium leading-6 text-gray-900">Doba
                    přípravy</label>
                <div class="mt-2">
                    <input type="number" step="0.1" name="doba_přípravy" id="doba_přípravy" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- jednotka (for doba přípravy) -->
            <div>
                <label for="jednotka_doba_přípravy" class="block text-sm font-medium leading-6 text-gray-900">Jednotka
                    doba přípravy</label>
                <div class="mt-2">
                    <input type="text" name="jednotka_doba_přípravy" id="jednotka_doba_přípravy" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- obtížnost -->
            <div>
                <label for="obtížnost" class="block text-sm font-medium leading-6 text-gray-900">Obtížnost</label>
                <div class="mt-2">
                    <select name="obtížnost" id="obtížnost" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="začátečník">Začátečník</option>
                        <option value="pokročilý">Pokročilý</option>
                        <option value="mistr">Mistr</option>
                    </select>
                </div>
            </div>

            <!-- autor článku -->
            <div>
                <label for="autor_článku" class="block text-sm font-medium leading-6 text-gray-900">Autor článku</label>
                <div class="mt-2">
                    <input type="text" name="autor_článku" id="autor_článku" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- počet porcí -->
            <div>
                <label for="počet_porcí" class="block text-sm font-medium leading-6 text-gray-900">Počet porcí</label>
                <div class="mt-2">
                    <input type="number" name="počet_porcí" id="počet_porcí" value="1" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <!-- ingredience -->
            <div class="sm:col-span-2">
                <label for="ingredience" class="block text-sm font-medium leading-6 text-gray-900">Ingredience</label>
                <div class="mt-2" id="ingredients-container">
                    <!-- Placeholder for dynamic ingredients -->
                </div>
                <div class="mt-2">
                    <button type="button" onclick="addIngredient()"
                            class="block px-8 rounded-md bg-indigo-600 py-2 text-white text-center shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        Přidat ingredienci
                    </button>
                </div>
            </div>

            <!-- postup -->
            <div class="sm:col-span-2">
                <label for="postup" class="block text-sm font-medium leading-6 text-gray-900">Postup</label>
                <div class="mt-2">
                    <textarea name="postup" id="postup" rows="4" required
                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                </div>
            </div>

            <!-- Submit button -->
            <div class="sm:col-span-2">
                <div class="mt-2">
                    <button type="submit"
                            class="block w-full rounded-md bg-indigo-600 py-2 text-white text-center shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        Vytvořit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
require 'components/footer.php';
?>
