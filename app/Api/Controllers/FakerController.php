<?php

namespace Api\Controllers;

use App\Dog;
use App\Http\Requests;
use Illuminate\Http\Request;
use Api\Requests\DogRequest;
use Api\Transformers\DogTransformer;
use Illuminate\Support\Str;

/**
 * @Resource('Dogs', uri='/dogs')
 */
class FakerController extends BaseController
{

    public function __construct() 
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Show me
     *
     * Get a JSON representation of all the dogs
     * 
     * @Get('/me')
     */
    public function getMe()
    {
        
        $user = new \stdClass();
        $faker = \Faker\Factory::create();

        $user->id =  0;
        $user->first_name =  $faker->firstName;
        $user->last_name =  $faker->lastName;
        $user->id_number =  $faker->idNumber;
        $user->cell =  $fake->e164PhoneNumber;
        $user->email =  $faker->email;
        $user->residential_address =  $faker->address;
        $user->postal_address =  $user->residential_address;
        $user->language =  $faker->languageCode;
        $user->blurb =  "";
        $user->profiles = collect([]);

        return response()->json($user);
    }

    /**
     * Show organisations
     *
     * Get a JSON representation
     * 
     * @Get('/me/schools')
     */
    public function schools()
    {
        $faker = \Faker\Factory::create();
        
        $howMany = rand(2, 9);

        $schools = collect([]);

        $cc = 0;
        for($cc; $cc <= $howMany; $cc++) {

            $school = new \stdClass();
            $school->id = $cc;
            $school->name = $faker->company;
            $school->medium_name = "Medium N";
            $school->short_name = "Medium N";
            $school->show_adverts = true;
            $school->color = $faker->hexcolor;
            $school->logo = $faker->imageUrl($width = 200, $height = 200);
            $school->telephone_number = $faker->e164PhoneNumber;
            $school->email_address = $faker->email;
            $school->physical_address = $faker->address;
            $school->url = "";
            $school->nick = $faker->stateAbbr;

            $schools->push($school);
        }

        return response()->json(['results' => $schools]);
    }

    /**
     * Show organisation
     *
     * Get a JSON representation
     * 
     * @Get('/me/schools/{id}')
     */
    public function school($id)
    {
        $faker = \Faker\Factory::create();
        
        $howMany = rand(2, 9);

        $school = new \stdClass();
        $school->id = $cc;
        $school->name = $faker->company;
        $school->medium_name = "Medium N";
        $school->short_name = "Medium N";
        $school->show_adverts = true;
        $school->color = $faker->hexcolor;
        $school->logo = $faker->imageUrl($width = 200, $height = 200);
        $school->telephone_number = $faker->e164PhoneNumber;
        $school->email_address = $faker->email;
        $school->physical_address = $faker->address;
        $school->url = "";
        $school->nick = $faker->stateAbbr;

        return response()->json($school);
    }


    /**
     * Show notices/feeds
     *
     * Get a JSON representation
     * 
     * @Get('/me/notices')
     */
    public function notices(Request $request)
    {
        $faker = \Faker\Factory::create();
        
        $howMany = rand(2, 9);

        $notices = collect([]);

        $cc = 0;
        for($cc; $cc <= $howMany; $cc++) {

            $notice = new \stdClass();
            $notice->id = $cc;
            $notice->school_id = 22;
            $notice->title = $faker->catchPhrase;
            $notice->body = $faker->realText($maxNbChars = 200, $indexSize = 2);
            $notice->created_at = $faker->date($format = 'Y-m-d', $max = 'now');
            $notice->updated_at = $faker->date($format = 'Y-m-d', $max = 'now');
            $notice->disabled_at = null;
            $notice->rsvp_date = null;
            $notice->icon_id = 3;
            $notice->is_read = $faker->boolean;
            $notice->questions = [];

            $notices->push($notice);
        }

        return response()->json(['results' => $notices]);
    }  

    /**
     * Show notice/feed
     *
     * Get a JSON representation
     * 
     * @Get('/me/notices/{id}')
     */
    public function notice($id)
    {
        $faker = \Faker\Factory::create();
        
        $notice = new \stdClass();
        $notice->id = $cc;
        $notice->school_id = 22;
        $notice->title = $faker->catchPhrase;
        $notice->body = $faker->realText($maxNbChars = 200, $indexSize = 2);
        $notice->created_at = $faker->date($format = 'Y-m-d', $max = 'now');
        $notice->updated_at = $faker->date($format = 'Y-m-d', $max = 'now');
        $notice->disabled_at = null;
        $notice->rsvp_date = null;
        $notice->icon_id = 3;
        $notice->is_read = $faker->boolean;
        $notice->questions = [];

        return response()->json($notices);
    }   

    /**
     * Legacy API
     *
     * Get a JSON representation
     */
    public function resolver(Request $request)
    {
        //get the URL parts
        $segments = $request->segments();

        //Get the method type
        $menthod = $request->getMethod();

        // Our merchandise
        $resource = new \stdClass;
        
        // The dude with the stuff
        $faker = \Faker\Factory::create();

        //Depending on the method type, we give respective data
        // $isMethod = $request->isMethod();

        $howMany = count($request->segments());
        
        // So its likely that if a user is requesting a specific resource, they are gonna make a call
        // to {'/resources/1'} in this instance the resource name cannot be the last segment

        $hasNumber = is_numeric($request->segment($howMany));

        if ( $hasNumber ) {
            $howMany = $howMany - 1;
        }

        $resource->name = $request->segment($howMany);

        $resource->params = collect($request->all());
        
        // Lets attach some uselless information
        $resource->meta = new \stdClass;
        $resource->meta->method = $menthod;
        
        // If the request had a number as a last segment, we resolve ounce, since we aksed for a specific reourceID
        return $hasNumber ? $this->resolveOnce($resource, $faker) : $this->resolveMany($resource, $faker);
    }

    /**
     * Legacy API
     *
     * @param resource
     * Get a JSON representation of a single resource
     */
    public function resolveOnce($resource, $faker)
    {
        
        $data = new \stdClass;

        // So you want one, lemme hook you up
        foreach ($resource->params as $key => $value) {
            
            if (is_array($value)) {

                // Array options mean someone is being smart, they want smart data, lets hook em up
                $nest = collect($value);

                $data->{$key} = collect([]);
                
                $howManyKids = rand(1,2);

                // Only support one nested child for now
                $nested = new \stdClass;
                foreach ($nest as $nestKey => $nestValue) {
                    $nested->{$nestKey} = $faker->{$nestValue};
                }

                $data->{$key}->push($nested);
                
                // $data->{$key} = $this->getFake($keyword, $faker, $length);
            } else {
                $data->{$key} = $faker->{$value};
            }
        }

        $singularName = Str::singular($resource->name);

        return response()->json([ $singularName => $data]);
    }
    

    /**
     * Legacy API
     *
     * @param resource
     * Get a JSON representation of a multiple resources
     */
    public function resolveMany($resource, $faker, $howMany = 10, $max = 50)
    {
        
        // I give you power and this is how you repay me
        $tooMuch = ( $howMany > $max );

        // Hold iiiitttt ... 
        $thisMany = $tooMuch ? $max : $howMany;

        $results = collect([]);

        // So you want this many resources, lemme hook you up
        for ($cc = 0; $cc <= $thisMany; $cc++) {

            // Lemme get this straight, you asked for a resource, with certain paramaters
            // with values and their respective types? guess so
            $result = new \stdClass;

            // So you want one, lemme hook you up
            foreach ($resource->params as $key => $value) {
                if (is_array($value)) {

                    // Array options mean someone is being smart, they want smart data, lets hook em up
                    $nest = collect($value);

                    $result->{$key} = collect([]);

                    // Only support one nested child for now
                    $nested = new \stdClass;
                    foreach ($nest as $nestKey => $nestValue) {
                        $nested->{$nestKey} = $faker->{$nestValue};
                    }

                    $result->{$key}->push($nested);
                    
                } else {
                    $result->{$key} = $faker->{$value};
                }
            }

            // add to results
            $results->push($result);            
        }


        return response()->json([ "results" => $results]);
    }    

    /**
     * Legacy API
     *
     * Uses faker to give us the fake awesomenes
     */
    public function getFake($keyword, $faker, $howMany = 10, $max = 30)
    {
        switch ($keyword) {
            case 'words': {
                return ($howMany > $max) ? $faker->words(10, $max) :$faker->words($howMany, $max);
            }
            case 'realText': {

                return ($howMany > $max) ? $faker->realText(10, $max) :$faker->words($howMany, $max);
            }
        }

        return $faker->{$keyword};
    }
}
