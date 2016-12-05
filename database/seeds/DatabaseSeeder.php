<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Keyword;
use App\Topic;
use App\Chemtext;
use App\Problem;
use App\Solution;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call('TopicKeywordSeeder');
        Model::unguard();

        // $this->call(UserTableSeeder::class);

        Model::reguard();
    }
}

class TopicKeywordSeeder extends Seeder
{
    public function run()
    {
		
		// DELETE TABLES */
		
        DB::table('keywords')->delete();
		DB::table('chemtexts')->delete();
		DB::table('problems')->delete();
		DB::table('topics')->delete();
		DB::table('keyword_topic')->delete();
		DB::table('chemtext_topic')->delete();
		DB::table('problem_topic')->delete();

        DB::table('users')->delete();

        $users = array(
                ['name' => 'Ryan Chenkie', 'email' => 'ryanchenkie@gmail.com', 'username' => 'ryan', 'admin' => "0", 'leader' => "1", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'Chris Sevilleja', 'email' => 'chris@scotch.io', 'username' => 'chris', 'admin' => "0", 'leader' => "0", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'Holly Lloyd', 'email' => 'holly@scotch.io', 'username' => 'holly', 'admin' => "0", 'leader' => "1", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'Adnan Kukic', 'email' => 'adnan@scotch.io', 'username' => 'adnan', 'admin' => "0", 'leader' => "0", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'John Nguyen', 'email' => 'john@gmail.com', 'username' => 'john', 'admin' => "1", 'leader' => "1", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'Larry Cooperman', 'email' => 'larry@gmail.com', 'username' => 'larry', 'admin' => "1", 'leader' => "1", 'password' => Hash::make('secret')],
                ['name' => 'Stefano Stefan', 'email' => 'stefano@gmail.com', 'username' => 'stefano', 'admin' => "1", 'leader' => "1", 'inSession' => "0", 'password' => Hash::make('secret')],
                ['name' => 'Taichi Sano', 'email' => 'taichi@gmail.com', 'username' => 'taichi', 'admin' => "1", 'leader' => "1", 'inSession' => "0", 'password' => Hash::make('secret')]
        );
            
        // Loop through each user above and create the record for them in the database
        foreach ($users as $user)
        {
            User::create($user);
        }

		
		// CREATE KEYWORDS

        $keyword1 = Keyword::create(array('word' => 'Reactions'));
        $keyword2 = Keyword::create(array('word' => 'Susan'));
        $keyword3 = Keyword::create(array('word' => 'King'));
        $keyword4 = Keyword::create(array('word' => 'electrophilic'));
        $keyword5 = Keyword::create(array('word' => 'Alkenes'));
        $keyword6 = Keyword::create(array('word' => 'addition'));
        $keyword7 = Keyword::create(array('word' => 'halides'));
		
		// CREATE CHEMTEXTS
		
		$chemtext1 = Chemtext::create(array('chemtext_type' => 'link', 'chemtext_name' => 'Chem Wiki', 'url' => "http://chemwiki.ucdavis.edu/Textbook_Maps/Organic_Chemistry_Textbook_Maps/Map%3A_Bruice_6ed_%22Organic_Chemistry%22/04%3A_The_Reactions_of_Alkenes/4.01%3A_The_Addition_of_a_Hydrogen_Halide_to_an_Alkene"));
		$chemtext2 = Chemtext::create(array('chemtext_type' => 'link', 'chemtext_name' => 'Chem Wiki', 'url' => "http://chemwiki.ucdavis.edu/Textbook_Maps/Organic_Chemistry_Textbook_Maps/Map%3A_Bruice_6ed_%22Organic_Chemistry%22/03%3A_Alkenes%3A_Structure%2C_Nomenclature%2C_and_an_Introduction_to_Reactivity_%E2%80%A2_Thermodynamics_and_Kinetics/3.3%3A_The_Structures_of_Alkenes"));
		$chemtext3 = Chemtext::create(array('chemtext_type' => 'link', 'chemtext_name' => 'Chem Wiki', 'url' => "http://chemwiki.ucdavis.edu/Textbook_Maps/Organic_Chemistry_Textbook_Maps/Map%3A_Bruice_6ed_%22Organic_Chemistry%22/03%3A_Alkenes%3A_Structure%2C_Nomenclature%2C_and_an_Introduction_to_Reactivity_%E2%80%A2_Thermodynamics_and_Kinetics/3.2%3A_The_Nomenclature_of_Alkenes"));
		
		
		// CREATE PROBLEMS
		$problem1 = Problem::create(array('problem_type' => 'link', 'problem_name' => 'Chem Wiki Prob', 'url' => "http://chemwiki.ucdavis.edu/Textbook_Maps/Organic_Chemistry_Textbook_Maps/Map%3A_Bruice_6ed_%22Organic_Chemistry%22/04%3A_The_Reactions_of_Alkenes/4.01%3A_The_Addition_of_a_Hydrogen_Halide_to_an_Alkene#Problems"));
		$problem2 = Problem::create(array('problem_type' => 'link', 'problem_name' => 'Chem Wiki Prob', 'url' => "http://chemwiki.ucdavis.edu/Textbook_Maps/Organic_Chemistry_Textbook_Maps/Map%3A_Bruice_6ed_%22Organic_Chemistry%22/03%3A_Alkenes%3A_Structure%2C_Nomenclature%2C_and_an_Introduction_to_Reactivity_%E2%80%A2_Thermodynamics_and_Kinetics/3.2%3A_The_Nomenclature_of_Alkenes#Problems"));
		
		// CREATE TOPICS 
		
        $topic1 = Topic::create(array(
            'topic_name' => 'Reactions of Alkenes: Electrophilic Addition',
            'video_url' => 'https://www.youtube.com/watch?start=1359&v=mWcr38tWpAU',
            'video_id' => 'mWcr38tWpAU',
            'video_description' => 'This is the second quarter of the organic chemistry series. Topics covered include: Fundamental concepts relating to carbon compounds with emphasis on structural theory and the nature of chemical bonding, stereochemistry, reaction mechanisms, and spectroscopic, physical, and chemical properties of the principal classes of carbon compounds'
        ));

        $topic2 = Topic::create(array(
            'topic_name' => 'Addition of Hydrogen Halides to Alkenes',
            'video_url' => 'https://www.youtube.com/watch?start=1568&v=mWcr38tWpAU',
            'video_id' => 'mWcr38tWpAU',
            'video_description' => 'This is the second quarter of the organic chemistry series. Topics covered include: Fundamental concepts relating to carbon compounds with emphasis on structural theory and the nature of chemical bonding, stereochemistry, reaction mechanisms, and spectroscopic, physical, and chemical properties of the principal classes of carbon compounds'
        ));

        $topic3 = Topic::create(array(
            'topic_name' => 'Alkenes: Introduction',
            'video_url' => 'https://www.youtube.com/watch?start=1040&v=mWcr38tWpAU',
            'video_id' => 'mWcr38tWpAU',
            'video_description' => 'This is the second quarter of the organic chemistry series. Topics covered include: Fundamental concepts relating to carbon compounds with emphasis on structural theory and the nature of chemical bonding, stereochemistry, reaction mechanisms, and spectroscopic, physical, and chemical properties of the principal classes of carbon compounds'
        ));
        $topic4 = Topic::create(array(
            'topic_name' => 'Nomenclature of Alkenes',
            'video_url' => 'https://www.youtube.com/watch?start=1040&v=mWcr38tWpAU',
            'video_id' => 'mWcr38tWpAU',
            'video_description' => 'This is the second quarter of the organic chemistry series. Topics covered include: Fundamental concepts relating to carbon compounds with emphasis on structural theory and the nature of chemical bonding, stereochemistry, reaction mechanisms, and spectroscopic, physical, and chemical properties of the principal classes of carbon compounds'
        ));
		
		// ATTACH KEYWORDS TO TOPICS 

        $topic1->keywords()->attach($keyword1->id);
        $topic1->keywords()->attach($keyword2->id);
        $topic1->keywords()->attach($keyword3->id);
        $topic1->keywords()->attach($keyword4->id);

        $topic2->keywords()->attach($keyword2->id);
        $topic2->keywords()->attach($keyword3->id);
        $topic2->keywords()->attach($keyword4->id);

        $topic3->keywords()->attach($keyword4->id);
        $topic3->keywords()->attach($keyword5->id);

        $topic4->keywords()->attach($keyword6->id);
        $topic4->keywords()->attach($keyword7->id);
		
		// ATTACH CHEMTEXTS TO TOPICS
		
		$topic2->chemtexts()->attach($chemtext1->id);
		$topic3->chemtexts()->attach($chemtext2->id);
		$topic4->chemtexts()->attach($chemtext3->id);
		
		// ATTACH PROBLEMS TO TOPICS
		
		$topic2->problems()->attach($problem1->id);
		$topic4->problems()->attach($problem2->id);

    }
}
