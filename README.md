**Documentație proiect PPAW**

Implementarea unei aplicații de gestionare a cheltuielilor în PHP folosind modelul MVC (Model-View-Controller) și testarea modelului API.

**Proiectare**

**Paradigme utilizate**

**- MVC** ajută la organizarea codului într-un mod logic, facilitând dezvoltarea, testarea și întreținerea aplicației. Pentru un manager de cheltuieli, MVC permite separarea clară între logica de gestionare a datelor financiare (Model), interfața cu care utilizatorul interacționează (View) și logica de procesare a cererilor utilizatorului (Controller), îmbunătățind modularitatea și flexibilitatea aplicației.
  Laravel este construit pe arhitectura MVC, ceea ce face această paradigma o alegere bună pentru dezvoltarea de aplicații cu acest framework.

**- API RESTful** dacă să fie dezvoltat mai departe pentru managerul de cheltuieli permite separarea backend-ului de frontend, ceea ce înseamnă că logica de business și gestionarea datelor sunt centralizate pe server, în timp ce interfața utilizatorului poate fi dezvoltată independent, folosind orice tehnologie de frontend. Aceasta oferă flexibilitate în dezvoltare și posibilitatea de a oferi acces la funcționalități prin diferite platforme (web, mobil, desktop) fără a duplica logica de business.

**- Code First cu migrări** - utilizarea aceste abordări permite modelarea datelor cu ușurință să creăm și să modificăm schema bazei de date din cod. Baza de date de test poate fi populată și distrusă automat în timpul testelor. Nu e nevoie de a scrie interogări SQL complexe, în caz că se modifică structura bazei de date (de ex. ștergerea logică, necesită coloane suplimentare de adăugat la o anumită entitate)


**Arhitectura aplicație** (modulele care interacționează pentru a obține funcționalitatea per ansamblu a aplicației)

**Concepte (entitati) utilizate:**  User, Expenses, ExpenseCategories, MonthyBudget, SavingsGoal, Raports, Notifications.

**User** - este elementul central si respectiv el are legături directe cu majoritatea entităților. Aceasta permite personalizarea și accesul la informații bazate pe profilul individual al fiecărui utilizator.

**Expenses**  - sunt înregistrări detaliate ale cheltuielilor efectuate de utilizatori, fiind clasificate în ExpenseCategories pentru o organizare mai bună.
Fiecare înregistrare în Expenses este legată de un singur User, dar un User poate avea multiple înregistrări în Expenses. Aceasta permite urmărirea cheltuielilor pe utilizator.

**ExpenseCategories -** o structură pentru organizarea cheltuielilor, facilitând analiza cheltuielilor pe categorii și bugetarea eficientă.

**MonthlyBudget** permite utilizatorilor să stabilească limite financiare pentru diverse categorii de cheltuieli sau pentru un buget general. Este strâns legat de Expenses pentru monitorizarea și controlul eficient al cheltuielilor față de bugetul stabilit. Fiecare buget este asociat cu un singur utilizator, dar un utilizator poate avea multiple bugete, potențial unul pentru fiecare categorie de cheltuieli.

**SavingGoal** - utilizează date din Expenses și MonthlyBudget pentru a oferi utilizatorilor un instrument de stabilire și monitorizare a obiectivelor de economii pe termen lung, promovând astfel responsabilitatea financiară și planificarea.

**Notifications** - sistem de comunicare proactivă care alertează utilizatorii despre evenimente relevante, cum ar fi apropierea de limitele bugetului sau atingerea obiectivelor de economii. Această entitate leagă logic toate celelalte componente, asigurându-se că utilizatorul este informat și actualizat.
![](/Users/astafivalentina/PhpstormProjects/ExpenseManager/img1.png)

**Implementare**

Nivel servicii (sau Business layer)
Este reprezentat de verificarea periodică a bugetului dacă utilizatorii și-au atins bugetul stabilit sau dacă au depășit un anumit prag (exemplu: atingerea sau triplarea bugetului inițial), primesc diverse notificări și beneficii cei ajută să monitorizeze banii și bugetul pe care îl au.
Avem un sistem de reward  abordat în Clasa ProcessRewardsCommand extinde clasa Command, ceea ce o face o comandă de consolă în Laravel. Aceasta conține logica pentru procesarea recompenselor utilizatorilor.
Pentru fiecare utilizator, se calculează diferența dintre bugetul lunar și cheltuielile. Dacă această diferență este pozitivă, se calculează punctele de recompensă (1 punct pentru fiecare 100 de unități monetare necheltuite) și se creează o nouă instanță Reward pentru a înregistra recompensa în baza de date.


**Secțiuni de cod / abordări deosebite**

_**Gestionarea bugetului lunar: metoda de afișare a tuturor bugetelor lunare_**


        public function index() {
        $user_id = auth()->user()->id;
        $today = Carbon::now();
        $start = $today->startOfMonth()->format('Y-m-d');
        $end = $today->endOfMonth()->format('Y-m-d');
        $user = User::withSum([
        'expenses' => function ($query) use ($start, $end) {
        $query->whereBetween('date', [$start, $end]);
        }
        ], 'Amount')
            ->withSum('monthlyBudget', 'Amount')
            ->find($user_id);

        $monthlyExpenses = Expense::selectRaw('SUM(expenses.Amount) as total_spent,  expense_categories.Name as category, monthly_budgets.Amount as budget')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')
            ->leftJoin('monthly_budgets', 'monthly_budgets.category_id', '=', 'expenses.category_id')
            ->where('expenses.user_id', $user_id)
            ->whereBetween('date', [$start, $end])
            ->groupBy('expenses.category_id')
            ->get();


**Implementarea ștergerii logică (Soft Delete)**

Pentru tabelul ExpensesCategorie a fost implementat acest concept deoarece Id acestei entități este folosit ca și cheie străină în alte tabele și ștergerea lui fizică ar implica ștergerea tuturor entităților adiacente (care au legătura cu ele) și implicit pierderea istoricului aplicației.
În Modelul ExpenseCategory trebuie să introducem  use SoftDeletes. Și deja logica o implementăm în Controlerului categoriilor.

**Logging**

Laravel utilizează biblioteca Monolog pentru logare, care oferă o varietate largă de handlere de logare (cum ar fi fișiere, syslog, baze de date, servicii cloud etc.).
Configurația pentru logare în Laravel este definită în fișierul config/logging.php, unde sunt configurate canalele de logare.

**Scrierea logurilor:**

Pentru a scrie în loguri, Laravel oferă diferite metode prin intermediul fațadei Log sau direct prin clasele helper logger().

**Exemple de utilizare:**

    use Illuminate\Support\Facades\Log;

    Log::info('Mesaj informativ', ['user_id' => 1]);
    Log::error('Eroare întâmpinată', ['exception' => $e]);

    // Sau folosind helper-ul
    logger()->warning('Avertisment', ['context' => 'detalii']);


Structura proiectului

Este divizat în două categorii:

**- MVC** (folderul tinaapp) cuprinde funcționaul și logica principală a aplicației.

**- API** fără interfață (folderul apiapp) aceste rute sunt încărcate de RouteServiceProvider și toate vor fi încărcate atribuit grupului de middleware „api”.

**- MVC** 
**Modelele** reprezintă structura datelor, logica de afaceri și regulile aplicației. În aplicatia Expense Manager, modelele interacționează cu baza de date și gestionează datele legate de utilizatori, cheltuieli, categorii de cheltuieli, notificări și bugete lunare.
User.php: gestionează datele utilizatorilor, inclusiv înregistrarea, autentificarea și profilul utilizatorului.
Expense.php: reprezintă o cheltuială individuală, gestionând informații precum suma, data și categoria asociată.
ExpenseCategory.php: definește diferite categorii pentru cheltuieli, permițând clasificarea și organizarea cheltuielilor.
MonthlyBudget.php: reprezintă bugetul alocat de un utilizator pentru o anumită perioadă, facilitând urmărirea și gestionarea cheltuielilor în raport cu bugetul.


**View-urile** sunt responsabile pentru prezentarea datelor utilizatorilor și pentru colectarea inputurilor utilizatorilor. Acestea generează interfața cu utilizatorul, fiind separate de logica de afaceri. View-urile sunt construite folosind template-uri Blade (șabloane oferit de Laravel).

**Pagini pentru gestionarea cheltuielilor:** permit utilizatorilor să adauge, să editeze și să șteargă cheltuieli.

**Pagini pentru categoriile de cheltuieli:** oferă o interfață pentru adăugarea și gestionarea categoriilor de cheltuieli.

**Pagini pentru bugetele lunare:** permit setarea și vizualizarea bugetelor lunare, precum și compararea acestora cu cheltuielile reale.

**Controllerele** preiau inputurile utilizatorilor, procesează cererile prin intermediul modelelor și determină view-ul care trebuie afișat ca răspuns. Ele servesc ca intermediari între modele și view-uri.

**ExpenseController:** gestionează logica pentru adăugarea, editarea și ștergerea cheltuielilor.

**ExpenseCategoryController:** manipulează adăugarea și gestionarea categoriilor de cheltuieli.

**MonthlyBudgetController: s**e ocupă cu setarea și urmărirea bugetelor lunare.

**AuthController:** gestionează autentificarea și înregistrarea utilizatorilor.

**Modelele** se concentrează pe logica de afaceri și pe manipularea datelor, view-urile se ocupă de prezentarea datelor, iar controllerele leagă aceste componente, gestionând fluxul de date și logica aplicației. Această separare clară îmbunătățește organizarea codului și facilitează dezvoltarea și testarea individuală a componentelor.
În Laravel nu există un concept denumit db context așa cum este în Entity Framework în .NET, dar funcționalitățile similare sunt realizate prin modele și migrări.
Migrările sunt un fel de control al versiunilor pentru schema bazei de date, permițând definirea și modificarea structurii bazei de date într-un mod programatic și versionabil. Migrările sunt stocate în directorul database/migrations și pot fi aplicate sau anulate prin intermediul CLI.

**- API cuprinde metodele:**

**GET** - pentru a prelua date (de exemplu, informații despre utilizatori, cheltuieli, categorii de cheltuieli sau bugete).
**GET by ID** - pentru a prelua date despre o anumită entitate.
**POST** - pentru a crea noi înregistrări (de exemplu, adăugarea unui nou utilizator, o nouă categorie de cheltuieli sau un nou buget).
**PUT** - pentru a actualiza înregistrări existente (doar la User este implementat).
**DELETE** - pentru a șterge înregistrări existente.
pentru User, Expenses Category, Expenses și Budget.
Logica de rutare este scrisă în routes/api.php.

Pentru a crea acest proiect avem nevoie de migratiile bazei de date, de controlerele (/app/Http/Controllers) și Modele (/app/Models).
Modelele în Laravel sunt folosite pentru a interacționa cu baza de date, fiecare model reprezintă o tabelă în baza de date și permite efectuarea operațiunilor CRUD pe acea tabelă.
Controlerele sunt utilizate pentru a gestiona logica aplicației. Ele preiau cererile de la client, procesează acele cereri (cu ajutorul modelelor, dacă este necesar), și apoi trimit un răspuns înapoi la client.
Laravel oferă middleware pentru autentificare, cum ar fi auth:sanctum pentru API-uri, care poate fi aplicat rutelor pentru a asigura că numai utilizatorii autentificați pot accesa anumite rute.

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
            return $request->user();
    });

Acest exemplu folosește middleware-ul auth:sanctum pentru a asigura că numai utilizatorii autentificați pot accesa informațiile utilizatorului curent. Dar în proiect au fost definite rute individuale pentru fiecare categorie.

**Exemple de utilizare a rutelor API:**

GET: http://127.0.0.1:8001/api/get/category
GET by ID: http://127.0.0.1:8000/api/get/category/1
POST: http://127.0.0.1:8000/api/post/category
Pentru efectuarea acestei metode este ca corpul metodei să conțină datele necesare în format JSON. De exemplu
   
     {
    "id": 15,
    "Name": "Test"
    }

La fel și pentru PUT (datele complete a întregii entități) și PATCH (datele parțiale a unei entități).
PUT/ PATCH: http://127.0.0.1:8000/api/delete/category/15
DELETE: http://127.0.0.1:8000/api/get/category/1


**Utilizare**

Aceste instrucțiuni vă vor ajută să creati o copie a proiectului în funcțiune pe mașina locală de care dispuneți pentru dezvoltare și testare.

**Cerințe preliminare (pentru a instala software-ul)**
- Git
- PHP
- MySQL
- Composer
- CLI
- Laravel Valet (opțional).
- Un server web XAMP, sau puteți utiliza alternative precum Nginx sau Apache.

(Resurse pentru instalarea și configurarea PHP Framework Laravel: https://kinsta.com/knowledgebase/install-laravel/)

**Instalare**

Clonează depozitul git de pe computer
$ git clone https://github.com/vastafi/Expense-Manager.git
(Vezi imaginea de mai jos, cum poți obține linkul)
![](/Users/astafivalentina/PhpstormProjects/ExpenseManager/img2.png)

De asemenea, puteți descărca întregul depozit ca fișier zip și despachetați pe computer dacă nu aveți git.
Configurarea Bazei de Date, cu ajutorul la XAMPP și phpMyAdmin, ne conectăm cu credentialele din fișierul env.file.
După clonarea aplicației, trebuie să instalați dependențele acesteia: $ cd și indicați directoriul aplicației, dar asigurati-va că aveti instalate cerințele preliminare indicate mai sus.
Apoi rulați: $ composer install
Actualizăm migrările în caz de necesitate: **$ php artisan migrate**

**Mod de utilizare**
După ce am actualizat dependențele putem starta proiectului utilizând comanda:  **$ php artisan serve**


	Astfel ni se va indica ruta aplicații noastre principale: http://127.0.0.1:8000

Înregistrarea utilizatorilor: utilizatorii noi trebuie să se înregistreze în aplicație furnizând detalii de bază, cum ar fi numele, adresa de email și parola. Aceasta se poate realiza prin accesarea paginii de înregistrare și completarea formularului corespunzător.
Autentificare: după înregistrare, utilizatorii pot accesa contul creat folosind credențialele de autentificare. Procesul de logare necesită introducerea adresei de email și a parolei stabilite la înregistrare.
Adăugarea cheltuielilor: odată autentificat, utilizatorul poate adăuga noi cheltuieli accesând secțiunea corespunzătoare și completând un formular cu suma cheltuită, data, categoria și o descriere opțională.
Vizualizarea cheltuielilor: cheltuielile adăugate pot fi vizualizate într-un tabel sub formă de listă, oferind utilizatorului o imagine clară asupra fluxului său financiar.
Editarea și stergerea cheltuielilor: utilizatorii pot edita sau șterge cheltuielile existente prin accesarea opțiunilor specifice fiecărei înregistrări.
Administrarea categoriilor de cheltuieli: utilizatorii pot defini categorii specifice pentru cheltuielile lor, cum ar fi "Mâncare", "Transport", "Facturi" etc.
Editarea și stergerea categoriilor: ele pot fi dasemenea, editate sau șterse în funcție de necesități.
Crearea unui buget: utilizatorii pot seta bugete lunare pentru diferite categorii, permițându-le să planifice și să monitorizeze cheltuielile în raport cu obiectivele financiare stabilite.

Pentru a executa sistemul de reward se utilizează comanda: **php artisan process-rewards**





