<?php include 'header.php'; ?>

<section>
    <h2>Bienvenue sur la page principale</h2>
    <p>Vous êtes sur la page d'accueil de notre site.</p>
    <?php
        //var_dump($_SESSION);
        if (isset($_SESSION['user'])) {
            echo '<p>Vous vous êtes miraculeusement connecté. Félicitations !</p>';
            // si non admin, on propose de le devenir (promis on en met pas beaucoup)
            if ($_SESSION['user']['admin'] == 0)
            {
                echo '<br/><p>(Vous êtes actuellement un utilisateur non admin)</p>';
                echo '<a href="index.php?ctrl=User&action=becomeAdmin">Devenir Admin (dépechez vous, accès limité)</a>';
            }
            else {
                echo '<br/><p>(Vous êtes actuellement un utilisateur admin)</p>';
            }
            echo '<br/><br/><strong><a href="index.php?ctrl=User&action=showUsers">Afficher la liste des utilisateurs (pour les admin uniquement)</a></strong><br/>';

        }
        else
        {
            echo '<p>Vous n\'êtes pas connecté.</p>';
        }

    ?>
</section>

<?php include 'footer.php'; ?>