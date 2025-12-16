<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsertMovie extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        $movies = [
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'poster' => 'http://is2.mzstatic.com/image/thumb/Video7/v4/5f/51/75/5f5175bf-5f14-39e7-9e37-30548c2d3044/source/1200x630bb.jpg',
                'description' => 'A thief who enters dreams to steal secrets must pull off his hardest job yet.'
            ],
            [
                'title' => 'viola come il mare',
                'genre' => 'Action',
                'poster' => 'http://localhost/bookmyshow_api/storage/app/public/viola.png',
                'description' => 'Batman faces his toughest opponent yet, the Joker, in Gotham City.'
            ],
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi',
                'poster' => 'https://cdnb.artstation.com/p/assets/images/images/063/023/133/large/ismail-poster-1-min.jpg?1684506383',
                'description' => 'A team of explorers travel through a wormhole in search of a new home for humanity.'
            ],
            [
                'title' => 'Titanic',
                'genre' => 'Romance',
                'poster' => 'https://i.etsystatic.com/23402008/r/il/823d00/2326734906/il_fullxfull.2326734906_keku.jpg',
                'description' => 'A tragic love story aboard the ill-fated RMS Titanic.'
            ],
            [
                'title' => 'Gladiator',
                'genre' => 'Historical Drama',
                'poster' => 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/09/image003.jpg',
                'description' => 'A Roman general seeks revenge against the corrupt emperor who murdered his family.'
            ],
            [
                'title' => 'The Matrix',
                'genre' => 'Sci-Fi',
                'poster' => 'https://2.bp.blogspot.com/-INZP7uildsE/UOM3yR2rEKI/AAAAAAAAIWE/XLxd3Dl8CAg/s1600/The%2BMatrix_03%2B%25281999%2529.jpg',
                'description' => 'A computer hacker learns that reality is a simulation and joins the fight for freedom.'
            ],
            [
                'title' => 'Avengers: Endgame',
                'genre' => 'Superhero',
                'poster' => 'https://images.hdqwalls.com/download/avengers-endgame-2019-ro-1280x2120.jpg',
                'description' => 'The Avengers assemble for one last battle against Thanos.'
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'poster' => 'https://image.tmdb.org/t/p/original/saHP97rTPS5eLmrLQEcANmKrsFl.jpg',
                'description' => 'The story of a man with a kind heart who experiences major historical events.'
            ],
            [
                'title' => 'The Lion King',
                'genre' => 'Animation',
                'poster' => 'https://static1.srcdn.com/wordpress/wp-content/uploads/2023/05/the-lion-king-poster.jpeg',
                'description' => 'A lion cub prince flees his kingdom after the death of his father.'
            ],
            [
                'title' => 'Jurassic Park',
                'genre' => 'Adventure',
                'poster' => 'https://i.pinimg.com/originals/a6/27/64/a62764fce3493992befe18fc2f530915.jpg',
                'description' => 'Dinosaurs are brought back to life in a theme park, leading to chaos.'
            ],
            [
                'title' => 'The Shawshank Redemption',
                'genre' => 'Drama',
                'poster' => 'https://www.vintagemovieposters.co.uk/wp-content/uploads/2015/11/IMG_0797.jpg',
                'description' => 'Two imprisoned men bond over years and find solace and redemption.'
            ],
            [
                'title' => 'Pulp Fiction',
                'genre' => 'Crime',
                'poster' => 'https://image.invaluable.com/housePhotos/heroesandlegends/12/723012/H22358-L288041343.jpg',
                'description' => 'Interwoven stories of crime and redemption in Los Angeles.'
            ],
            [
                'title' => 'Avatar',
                'genre' => 'Sci-Fi',
                'poster' => 'https://i.etsystatic.com/34708433/r/il/15fb83/4498029997/il_1080xN.4498029997_cjib.jpg',
                'description' => 'A paraplegic Marine joins an alien world and struggles between two sides.'
            ],
            [
                'title' => 'The Godfather',
                'genre' => 'Crime',
                'poster' => 'https://www.cinemaclock.com/images/posters/1000x1500/26/the-godfather-1972-us-poster.jpg',
                'description' => 'The aging patriarch of a crime dynasty transfers control to his reluctant son.'
            ],
            [
                'title' => 'Frozen',
                'genre' => 'Animation',
                'poster' => 'https://lumiere-a.akamaihd.net/v1/images/p_frozen_18373_3131259c.jpeg',
                'description' => 'Two sisters struggle with love, magic, and saving their kingdom.'
            ],
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
}
