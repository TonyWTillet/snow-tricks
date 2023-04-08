<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public const TRICK_0 = 'trick-0';
    public const TRICK_1 = 'trick-1';
    public const TRICK_2 = 'trick-2';
    public const TRICK_3 = 'trick-3';
    public const TRICK_4 = 'trick-4';
    public const TRICK_5 = 'trick-5';
    public const TRICK_6 = 'trick-6';
    public const TRICK_7 = 'trick-7';
    public const TRICK_8 = 'trick-8';
    public const TRICK_9 = 'trick-9';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $trickFixtures = [
            0 => [
                'name' => 'Le Nose Press/Tail Press',
                'content' => 'Avant de faire une explication détaillée sur le Nose Press et le Tail Press, il faut d’abord connaitre les termes anglais du ‘’Nose’’ et du ‘’Tail’’, qui se traduisent par ‘’le nez’’ et ‘’la queue’’ en français. Sur un snowboard, on appelle le devant de la planche le Nose/nez et l’arrière de la planche le Tail/queue. Que tu sois en Regular, en Goofy ou en Switch, le nez sera toujours pointé dans la direction de la piste.<br>Revenons maintenant à notre explication. Un Tail Press consiste à simplement transférer ton poids sur ta jambe arrière tout en soulevant légèrement ta jambe avant, ce qui soulèvera l\'avant de la planche. Le Nose Press est l\'inverse. Le poids est transféré sur la jambe avant et l\'arrière de la planche se plie vers le haut. Il s\'agit d\'une astuce classique, mais basique, qui donne à n\'importe quel planchiste un look "steezy". À noter que, si tu as une planche flexible, tu devrais avoir plus d’effets et de hauteurs au moment de soulever ta planche !<br>Le conseil des pros : Commence par le faire sur un espace plat sans mouvement pour trouver le bon équilibre, et dès que tu arrives à le faire sur le plat sans bouger, tu peux ensuite le faire en mouvement. Si tu maitrises les deux figures en mouvement, tu peux essayer de le faire sur un box au snowpark.',
                ],
            1 => [
                'name' => 'Le Butter',
                'content' => 'Le butter est la progression naturelle du Nose et du Tail Press, et il est à la base de nombreux tricks freestyle. Il est donc indispensable de le réussir. De son nom anglophone, on peut s’imaginer en train de glisser un couteau dans du beurre pour tes tartines le matin, ce qui laissera forcément une trace dans le beurre. Eh bah dans le contexte du snowboard, le snowboard devient le couteau qui glisse sur la neige. C’est aussi simple que ça ! <br>Pour réussir cette figure amusante, il s\'agit de soulever le nez ou la queue de la planche dans les airs, tout en restant en contact avec la neige, ce qui te permettra de faire des variations de spin tout en glissant sur la neige. Habituellement, quand tu fais des virages pour changer de direction sur les pistes, tu utiliseras les lames/bords de ton snowboard pour garder le contrôle. Mais pour le Butter, il faut essayer de rester bien centré sur ta planche, car si tu utilises les lames/bords de ton snowboard, tu risques de tomber. Le but est de garder sa planche au plat sur la neige tout en gardant le Nose ou le Tail en l\’air, pendant que tu glisses sur la piste.',
                ],
            2 => [
                'name' => 'Le Ollie/Nollie',
                'content' => 'Dès que tu auras perfectionné les Noses Press, les Tails Press et les Butters, tu pourras t’attaquer au Ollie et au Nollie. Le Ollie est l\'une des figures fondamentales qu\'il faut maîtriser en snowboard, car tu l’utiliseras pour prendre de l\’air sur des kickers ou pour sauter sur des rails.<br>Pour faire un Ollie, déplace ton poids sur ta jambe arrière, comme pour les Tails Press, et dans un mouvement rapide, saute en levant ta jambe avant. Puis, avec un peu d\'effort, lève également ta jambe arrière, de sorte que tes pieds soient parallèles et que ta planche soit à l\'horizontale par rapport au sol.<br>Un Nollie se fait sur le Nose de la planche et consiste à déplacer ton poids sur ta jambe avant, puis à sauter depuis ta jambe arrière. Comme pour le Ollie, tu dois ensuite faire monter ta jambe avant pour qu’elle rejoigne ta jambe arrière, ce qui créera un saut en parallèle avec le sol. Plutôt cool, non ?',
                ],
            3 => [
                'name' => 'Le Frontside/Backside 180-360',
                'content' => 'Tu verras que faire des rotations sur la neige ou dans les airs avec ton snowboard est une chose amusante quelle que soit la situation. Toutes les rotations en snowboard sont exprimées en degrés, ce qui veut dire que si tu fais un cercle complet sur toi-même, ça sera égal à 360 degrés. Si tu fais deux tours sur toi-même, on multiplie 360 degrés par 2 et on arrive à 720 degrés et ainsi de suite. Tu peux aussi diviser 360 par 2 pour arriver à faire une demi-rotation de 180 degrés. Qui a dit que les snowboardeurs n’étaient pas bons en mathématiques !?<br>Il y a deux sens de rotations en snowboard : les sauts en Frontside (côté frontal) et les sauts en Backside (côté dorsal). Si tu rides en Regular, un saut Frontside est une rotation dans laquelle les talons mènent et tournent dans le sens inverse des aiguilles d\'une montre, au départ du saut. Et un saut en Backside pour les Regulars, est une rotation dans le sens des aiguilles d\'une montre, où les orteils mènent et changent de jambe. En Goofy, ça sera l’inverse pour les deux figures. <br>Le conseil des pros : Commence par faire des sauts à 180 degrés sur place pour trouver les bonnes sensations et atterrir en Switch. Tu peux ensuite le faire avec du mouvement pour voir comment tu gères les rotations et les atterrissages avec ton mauvais pied en avant. La progression naturelle est de le faire sur un saut et d’augmenter le nombre de rotations au fur et à mesure.',
                ],
            4 => [
                'name' => 'Le Indy Grab',
                'content' => 'Le Indy Grab est la figure de base pour saisir sa planche dans les airs, mais il y en a beaucoup d\'autres pour diversifier tes sauts. Pour ceux qui cherchent à impressionner, essayez le Tail Grab comme prochaine étape. Comme pour le Indy Grab, commence par faire un Ollie pour prendre de la hauteur depuis le saut, et une fois en l’air, attrapes la queue de la planche avec ta main arrière. C\'est aussi simple que cela (ou pas si simple, mais personne ne se doutera de rien).',
                ],
            5 => [
                'name' => 'Le Backflip',
                'content' => 'Le Backflip est l\'une des figures les plus emblématiques du snowboard, et on ne pouvait pas l’exclure de notre liste ! Une fois que tu as quitté le kicker et que tu as assez de hauteur et d\'élan, il faut jeter ton poids en arrière pour faire une rotation verticale - ou un Flip - pour avoir la tête en bas et les jambes en haut, tout en relâcher tes jambes au bon moment pour atterrir. Tu as probablement eu quelques accidents désagréables en le perfectionnant, mais cela en vaut la peine lorsque tu vois les visages et les acclamations de tes spectateurs.<br>Le conseil des pros : Les snowboardeurs professionnels s’entrainent à faire des Backflips sur des trampolines en été, pour les aider à faire les bons mouvements au bon moment au snowpark en hiver.',
                ],
            6 => [
                'name' => 'Une rotation Cork',
                'content' => 'Le Cork est une variation avancée d\'une rotation, où tu ne te contentes pas de tourner à la verticale, mais où ton corps tourne en fait hors de l\'axe. Au milieu du saut, tu peux faire un 360 en Frontside ou en Backside, pendant que tes jambes et ta planche tournent vers le haut, de sorte que le haut de ton corps se trouve sous ta planche. Pour les experts, l\'exécution du Cork te fera tourner à l\'envers, ce qui est assez fou et difficile à comprendre.',
                ],
            7 => [
                'name' => 'Le Nose Grab',
                'content' => 'Le Nose Grab est l’inverse du Tail Grab. Au lieu d’attraper la queue de la planche, il faut attraper le nez de la planche. Pour ce trick, il faut essayer de plier ta jambe avant après le Ollie, pour faire remonter la planche dans un mouvement plus vertical en l’air, ce qui te donnera plus de facilité à attraper le Nose.<br>À noter que pour le Nose et le Tail Grab, il faut quand même avoir un peu plus de hauteur pour avoir le temps d’attraper la planche et de la relâcher à temps avant l’atterrissage. Cela veut dire que tu devras prendre plus de vitesse avant le saut et ajouter plus de puissance à ton Ollie.',
                ],
            8 => [
                'name' => 'Le Switch',
                'content' => 'Le Indy Grab est la figure de base pour saisir sa planche dans les airs, mais il y en a beaucoup d\'autres pour diversifier tes sauts. Pour ceux qui cherchent à impressionner, essayez le Tail Grab comme prochaine étape. Comme pour le Indy Grab, commence par faire un Ollie pour prendre de la hauteur depuis le saut, et une fois en l’air, attrapes la queue de la planche avec ta main arrière. C\'est aussi simple que cela (ou pas si simple, mais personne ne se doutera de rien).',
                ],
            9 => [
                'name' => 'Le Frontside Boardslide',
                'content' => ' Il s\'agit de glisser jusqu\'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.',
            ],
        ];

        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setName($trickFixtures[$i]['name']);
            $trick->setContent($trickFixtures[$i]['content']);
            $trick->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $trick->setUpdatedAt($faker->dateTimeBetween('-3 months'));
            if ($i % 2 == 0) {
                $trick->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            } else {
                $trick->setUser($this->getReference(UserFixtures::USER_REFERENCE));
            }
            if ($i < 3) {
                $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_DEBUTANT));
            } elseif ($i < 6) {
                $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_INTERMEDIAIRE));
            }
            else {
                $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_EXPERIMENTE));
            }
            $trick->setDefaultPicture(null);
            $this->setReference(self::TRICK_0, $trick);
            $manager->persist($trick);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
