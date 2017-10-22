<h1>CriticSki</h1>
<p>
    Site de notation de stations de ski pour mieux choisir son séjour en fonctions de ses critères et préférences.<br>
    <i>Review website for ski resorts to better choose your stay according to its criteria and preferences.</i>
</p>
<h3>Prequisites</h3>
<ul>
    <li>PHP 7.0+</li>
    <li>Composer</li>
    <li>Git</li>
</ul>
<h3>Setup</h3>
<p>
    Si vous utilisez une machine virtuelle vagrant, vérifiez qu'elle soit lancée et connectez-vous en SSH.<br>
    <i>If you're using a vagrant virtual machine, make sure it's up and connect to SSH.</i>
</p>
<pre>
vagrant up
vagrant ssh
</pre>
<p>
    Clonez le répertoire git et placez-vous dans le dossier de projet.<br>
    <i>Clone git repository and get in project folder.</i>
</p>
<pre>
git clone https://github.com/robinpichon/station-ski.git criticski
cd criticski
</pre>
<p>
    Exécutez l'installateur du projet et lancez le serveur web.<br>
    <i>Execute project install and start web server.</i>
</p>
<pre>
make install
make start
</pre>
<p>
    Le site devrait être disponible à l'adresse <a href="http://localhost:1337">http://localhost:1337</a>.<br>
    <i>The website must be available from address <a href="http://localhost:1337">http://localhost:1337</a>.</i>
</p>
<h3>Admin accounts</h3>
<p>
    Un compte administrateur est automatiquement créé à la fin du processus d'installation.<br>
    Les identifiants générés aléatoirement sont affichés sous cette forme quand l'installation se termine.<br>
    <i>An admin account is automaticaly created at the end of the install process.<br>
    Random credentials are displayed like this when install ends.</i>
</p>
<pre>
Generating admin account... OK
=============================================================
Administrator account credentials
Email: admin_xxxxxxxxx@admin.fr
Password: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
=============================================================
</pre>
<p>
    Vous pouvez créer autant de comptes administrateur que vous le souhaitez en utilisant la commande<br>
    <i>You can create as many admin accounts as you want by using the command</i>
</p>
<pre>
make admin
</pre>
<h3>Make commands</h3>
<p>
    Démarrer le serveur web.<br>
    <i>Start web server.</i>
</p>
<pre>
make start
</pre>
<p>
    Arrêter le serveur web.<br>
    <i>Stop web server.</i>
</p>
<pre>
make stop
</pre>
<p>
    Appliquer le jeu d'essai.<br>
    <i>
        Apply fixtures.<br>
        <ul>
            <li>(Re)create database</li>
            <li>Update database schema</li>
            <li>Install assets</li>
            <li>Generate stations</li>
            <li>Generate users</li>
            <li>Generate reviews</li>
            <li>Clear avatars directory</li>
        </ul>
    </i>
</p>
<pre>
make fixtures
</pre>
<p>
    Créer un compte administrateur.<br>
    <i>Create admin account.</i>
</p>
<pre>
make admin
</pre>
<h3>TODO</h3>
General
<ul>
    <li>Implement TripAdvisor API</li>
</ul>
User
<ul>
    <li>Review edit</li>
</ul>
Admin
<ul>
    <li>User edit</li>
</ul>
