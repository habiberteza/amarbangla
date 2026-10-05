<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchService
{
    /**
     * Dictionary mapping English, Banglish, and Bengali synonyms/brand terms.
     */
    protected static array $synonyms = [
        // Bakery & Toast & Biscuits
        'toast' => ['toast', 'tost', 'টোস্ট', 'টষ্ট', 'রুস্ক', 'rusk'],
        'tost' => ['toast', 'tost', 'টোস্ট', 'টষ্ট', 'রুস্ক'],
        'টোস্ট' => ['toast', 'tost', 'টোস্ট', 'টষ্ট'],
        'টষ্ট' => ['toast', 'tost', 'টোস্ট', 'টষ্ট'],
        'special' => ['special', 'স্পেশাল', 'বিশেষ'],
        'স্পেশাল' => ['special', 'স্পেশাল'],
        'biscuit' => ['biscuit', 'biskut', 'biscuits', 'বিস্কুট', 'বিস্কিট', 'কুকিজ', 'cookie', 'cookies'],
        'biskut' => ['biscuit', 'biskut', 'বিস্কুট'],
        'বিস্কুট' => ['biscuit', 'biskut', 'বিস্কুট', 'বিস্কিট'],
        'bread' => ['bread', 'pauruti', 'ruti', 'bun', 'পাউরুটি', 'রুটি', 'বান'],
        'pauruti' => ['bread', 'pauruti', 'পাউরুটি'],
        'পাউরুটি' => ['bread', 'pauruti', 'পাউরুটি'],
        'cake' => ['cake', 'কেক', 'পেস্ট্রি', 'pastry'],
        'কেক' => ['cake', 'কেক'],

        // Popular Brands in Bangladesh
        'pran' => ['pran', 'praan', 'প্রাণ'],
        'praan' => ['pran', 'praan', 'প্রাণ'],
        'প্রাণ' => ['pran', 'praan', 'প্রাণ'],
        'alltime' => ['alltime', 'all time', 'অলটাইম', 'অল টাইম'],
        'অলটাইম' => ['alltime', 'all time', 'অলটাইম'],
        'sajeeb' => ['sajeeb', 'সজীব'],
        'সজীব' => ['sajeeb', 'সজীব'],
        'kishwan' => ['kishwan', 'কিশোয়ান', 'কিশওয়ান'],
        'কিশোয়ান' => ['kishwan', 'কিশোয়ান'],
        'dekko' => ['dekko', 'ডেকো'],
        'ডেকো' => ['dekko', 'ডেকো'],
        'olympic' => ['olympic', 'অলিম্পিক'],
        'অলিম্পিক' => ['olympic', 'অলিম্পিক'],
        'cocola' => ['cocola', 'কোকোলা'],
        'কোকোলা' => ['cocola', 'কোকোলা'],
        'ifad' => ['ifad', 'ইফাদ'],
        'ইফাদ' => ['ifad', 'ইফাদ'],
        'danish' => ['danish', 'ড্যানিশ'],
        'ড্যানিশ' => ['danish', 'ড্যানিশ'],
        'goldmark' => ['goldmark', 'গোল্ডমার্ক'],
        'ispahani' => ['ispahani', 'ইস্পাহানি'],
        'ইস্পাহানি' => ['ispahani', 'ইস্পাহানি'],
        'wellfood' => ['well food', 'wellfood', 'ওয়েল ফুড'],
        'bakersfarm' => ['bakers farm', 'বেক ফার্ম'],
        'fresh' => ['fresh', 'ফ্রেশ'],
        'ফ্রেশ' => ['fresh', 'ফ্রেশ'],
        'teer' => ['teer', 'তীর'],
        'তীর' => ['teer', 'তীর'],
        'rupchanda' => ['rupchanda', 'রূপচাঁদা'],
        'রূপচাঁদা' => ['rupchanda', 'রূপচাঁদা'],
        'radhuni' => ['radhuni', 'রাঁধুনী'],
        'রাঁধুনী' => ['radhuni', 'রাঁধুনী'],
        'chashi' => ['chashi', 'চাষী'],
        'চাষী' => ['chashi', 'চাষী'],
        'bashundhara' => ['bashundhara', 'বসুন্ধরা'],
        'বসুন্ধরা' => ['bashundhara', 'বসুন্ধরা'],
        'aarong' => ['aarong', 'আড়ং', 'আড়ং'],
        'আড়ং' => ['aarong', 'আড়ং'],
        'akij' => ['akij', 'আকিজ'],
        'আকিজ' => ['akij', 'আকিজ'],
        'meghna' => ['meghna', 'মেঘনা'],
        'মেঘনা' => ['meghna', 'মেঘনা'],
        'aci' => ['aci', 'এসিআই'],
        'square' => ['square', 'স্কয়ার'],
        'nestle' => ['nestle', 'নেসলে'],
        'নেসলে' => ['nestle', 'নেসলে'],
        'maggi' => ['maggi', 'ম্যাগি'],
        'ম্যাগি' => ['maggi', 'ম্যাগি'],
        'dano' => ['dano', 'ডানো'],
        'ডানো' => ['dano', 'ডানো'],
        'marks' => ['marks', 'মার্কস'],
        'diploma' => ['diploma', 'ডিপ্লোমা'],
        'milkvita' => ['milk vita', 'মিল্ক ভিটা'],
        'horlicks' => ['horlicks', 'হরলিক্স'],
        'complan' => ['complan', 'কমপ্ল্যান'],
        'closeup' => ['closeup', 'ক্লোজআপ'],
        'ক্লোজআপ' => ['closeup', 'ক্লোজআপ'],
        'colgate' => ['colgate', 'কোলগেট'],
        'কোলগেট' => ['colgate', 'কোলগেট'],
        'lifebuoy' => ['lifebuoy', 'লাইফবয়'],
        'লাইফবয়' => ['lifebuoy', 'লাইফবয়'],
        'savlon' => ['savlon', 'স্যাভলন'],
        'স্যাভলন' => ['savlon', 'স্যাভলন'],
        'dettol' => ['dettol', 'ডেটোল'],
        'ডেটোল' => ['dettol', 'ডেটোল'],
        'harpic' => ['harpic', 'হারপিক'],
        'হারপিক' => ['harpic', 'হারপিক'],
        'surf' => ['surf excel', 'সার্ফ এক্সেল', 'surf'],
        'rin' => ['rin', 'রিন'],
        'wheel' => ['wheel', 'হুইল'],
        'tibet' => ['tibet', 'তিব্বত'],
        'parachute' => ['parachute', 'প্যারাস্যুট'],
        'প্যারাস্যুট' => ['parachute', 'প্যারাস্যুট'],

        // Rice (চাল)
        'rice' => ['rice', 'chal', 'cal', 'চাল', 'চাউল'],
        'chal' => ['rice', 'chal', 'cal', 'চাল', 'চাউল'],
        'cal' => ['rice', 'chal', 'cal', 'চাল', 'চাউল'],
        'চাল' => ['rice', 'chal', 'cal', 'চাল', 'চাউল'],
        'চাউল' => ['rice', 'chal', 'cal', 'চাল', 'চাউল'],
        'miniket' => ['miniket', 'minicate', 'মিনিকেট'],
        'মিনিকেট' => ['miniket', 'minicate', 'মিনিকেট'],
        'najirshail' => ['najirshail', 'nazirshail', 'নাজিরশাইল'],
        'নাজিরশাইল' => ['najirshail', 'nazirshail', 'নাজিরশাইল'],
        'chinigura' => ['chinigura', 'chiniguda', 'চিনিগুঁড়া', 'চিনিকুড়া', 'চিনিগুড়া'],
        'চিনিগুঁড়া' => ['chinigura', 'chiniguda', 'চিনিগুঁড়া', 'চিনিকুড়া'],
        'katarivog' => ['katarivog', 'kataribhog', 'কাটারিভোগ'],
        'কাটারিভোগ' => ['katarivog', 'kataribhog', 'কাটারিভোগ'],
        'basmati' => ['basmati', 'বাসমতি'],
        'বাসমতি' => ['basmati', 'বাসমতি'],
        'polao' => ['polao', 'pulao', 'পোলাও'],
        'পোলাও' => ['polao', 'pulao', 'পোলাও'],

        // Dal (ডাল)
        'dal' => ['dal', 'daal', 'lentil', 'ডাল'],
        'daal' => ['dal', 'daal', 'lentil', 'ডাল'],
        'lentil' => ['dal', 'daal', 'lentil', 'ডাল'],
        'ডাল' => ['dal', 'daal', 'lentil', 'ডাল'],
        'moshur' => ['moshur', 'masoor', 'মসুর'],
        'মসুর' => ['moshur', 'masoor', 'মসুর'],
        'mug' => ['mug', 'moong', 'মুগ'],
        'মুগ' => ['mug', 'moong', 'মুগ'],
        'chola' => ['chola', 'chickpea', 'ছোলা', 'বুট'],
        'ছোলা' => ['chola', 'chickpea', 'ছোলা'],

        // Oil (তেল)
        'oil' => ['oil', 'tel', 'তেল'],
        'tel' => ['oil', 'tel', 'তেল'],
        'তেল' => ['oil', 'tel', 'তেল'],
        'soyabean' => ['soyabean', 'soybean', 'সয়াবিন', 'সয়াবিন'],
        'সয়াবিন' => ['soyabean', 'soybean', 'সয়াবিন', 'সয়াবিন'],
        'mustard' => ['mustard', 'sorisha', 'shorisha', 'সরিষা'],
        'sorisha' => ['mustard', 'sorisha', 'সরিষা'],
        'সরিষা' => ['mustard', 'sorisha', 'সরিষা'],
        'ghee' => ['ghee', 'ghi', 'ঘি'],
        'ঘি' => ['ghee', 'ghi', 'ঘি'],

        // Eggs, Milk, Dairy
        'egg' => ['egg', 'eggs', 'dim', 'ডিম', 'ফার্ম'],
        'eggs' => ['egg', 'eggs', 'dim', 'ডিম'],
        'dim' => ['egg', 'eggs', 'dim', 'ডিম'],
        'ডিম' => ['egg', 'eggs', 'dim', 'ডিম'],
        'milk' => ['milk', 'dudh', 'dairy', 'দুধ'],
        'dudh' => ['milk', 'dudh', 'দুধ'],
        'দুধ' => ['milk', 'dudh', 'দুধ'],
        'butter' => ['butter', 'মাখন'],
        'মাখন' => ['butter', 'মাখন'],

        // Vegetables (শাকসবজি)
        'potato' => ['potato', 'alu', 'aloo', 'আলু'],
        'alu' => ['potato', 'alu', 'aloo', 'আলু'],
        'আলু' => ['potato', 'alu', 'aloo', 'আলু'],
        'onion' => ['onion', 'peyaj', 'piyaj', 'পেঁয়াজ', 'পেঁয়াজ', 'পিয়াজ'],
        'peyaj' => ['onion', 'peyaj', 'পেঁয়াজ', 'পেঁয়াজ'],
        'পেঁয়াজ' => ['onion', 'peyaj', 'পেঁয়াজ', 'পেঁয়াজ'],
        'পেঁয়াজ' => ['onion', 'peyaj', 'পেঁয়াজ', 'পেঁয়াজ'],
        'garlic' => ['garlic', 'roshun', 'rosun', 'রসুন'],
        'roshun' => ['garlic', 'roshun', 'রসুন'],
        'রসুন' => ['garlic', 'roshun', 'রসুন'],
        'ginger' => ['ginger', 'ada', 'আদা'],
        'ada' => ['ginger', 'ada', 'আদা'],
        'আদা' => ['ginger', 'ada', 'আদা'],
        'chilli' => ['chilli', 'chili', 'morich', 'jhal', 'মরিচ', 'ঝাল'],
        'morich' => ['chilli', 'morich', 'jhal', 'মরিচ', 'ঝাল'],
        'মরিচ' => ['chilli', 'morich', 'jhal', 'মরিচ'],
        'vegetable' => ['vegetable', 'vegetables', 'sobji', 'shobji', 'সবজি', 'শাকসবজি'],
        'vegetables' => ['vegetable', 'vegetables', 'sobji', 'সবজি'],
        'sobji' => ['vegetable', 'sobji', 'সবজি'],
        'সবজি' => ['vegetable', 'vegetables', 'sobji', 'সবজি'],
        'tomato' => ['tomato', 'টমেটো'],
        'টমেটো' => ['tomato', 'টমেটো'],
        'cucumber' => ['cucumber', 'shosha', 'শসা', 'সালাদ'],
        'শসা' => ['cucumber', 'shosha', 'শসা'],
        'carrot' => ['carrot', 'gajor', 'গাজর'],
        'গাজর' => ['carrot', 'gajor', 'গাজর'],
        'lemon' => ['lemon', 'lime', 'lebu', 'লেবু'],
        'লেবু' => ['lemon', 'lime', 'lebu', 'লেবু'],
        'brinjal' => ['brinjal', 'eggplant', 'begun', 'বেগুন'],
        'begun' => ['brinjal', 'eggplant', 'begun', 'বেগুন'],
        'বেগুন' => ['brinjal', 'eggplant', 'begun', 'বেগুন'],
        'potol' => ['potol', 'পটল'],
        'পটল' => ['potol', 'পটল'],
        'dherosh' => ['okra', 'dherosh', 'ঢেঁড়শ', 'ঢেঁড়শ', 'ভেন্ডি'],
        'ঢেঁড়শ' => ['okra', 'dherosh', 'ঢেঁড়শ'],
        'lau' => ['lau', 'bottle gourd', 'লাউ'],
        'লাউ' => ['lau', 'bottle gourd', 'লাউ'],
        'shak' => ['shak', 'spinach', 'শাক', 'পালং', 'লাল শাক', 'পুঁই শাক'],
        'শাক' => ['shak', 'spinach', 'শাক'],

        // Meat & Fish (মাছ মাংস)
        'meat' => ['meat', 'beef', 'chicken', 'mutton', 'মাংস', 'গোস্ত'],
        'মাংস' => ['meat', 'beef', 'chicken', 'mutton', 'মাংস', 'গোস্ত'],
        'chicken' => ['chicken', 'murgi', 'মুরগি', 'মুরগী'],
        'murgi' => ['chicken', 'murgi', 'মুরগি'],
        'মুরগি' => ['chicken', 'murgi', 'মুরগি'],
        'beef' => ['beef', 'gorur', 'গরুর', 'গরু'],
        'গরু' => ['beef', 'gorur', 'গরুর', 'গরু'],
        'mutton' => ['mutton', 'khasir', 'খাসির', 'খাসি'],
        'খাসি' => ['mutton', 'khasir', 'খাসির', 'খাসি'],
        'fish' => ['fish', 'mach', 'মাছ'],
        'mach' => ['fish', 'mach', 'মাছ'],
        'মাছ' => ['fish', 'mach', 'মাছ'],
        'ilish' => ['ilish', 'hilsha', 'ইলিশ'],
        'ইলিশ' => ['ilish', 'hilsha', 'ইলিশ'],
        'rui' => ['rui', 'rohu', 'রুই'],
        'রুই' => ['rui', 'rohu', 'রুই'],
        'chingri' => ['chingri', 'prawn', 'shrimp', 'চিংড়ি', 'চিংড়ি'],
        'চিংড়ি' => ['chingri', 'prawn', 'shrimp', 'চিংড়ি'],

        // Daily Essentials: Salt, Sugar, Tea, Flour
        'salt' => ['salt', 'lobon', 'nobon', 'লবণ', 'নুন'],
        'lobon' => ['salt', 'lobon', 'লবণ'],
        'লবণ' => ['salt', 'lobon', 'লবণ'],
        'sugar' => ['sugar', 'chini', 'cini', 'চিনি'],
        'chini' => ['sugar', 'chini', 'cini', 'চিনি'],
        'cini' => ['sugar', 'chini', 'cini', 'চিনি'],
        'চিনি' => ['sugar', 'chini', 'cini', 'চিনি'],
        'tea' => ['tea', 'cha', 'চা'],
        'cha' => ['tea', 'cha', 'চা'],
        'চা' => ['tea', 'cha', 'চা'],
        'coffee' => ['coffee', 'কফি'],
        'কফি' => ['coffee', 'কফি'],
        'flour' => ['flour', 'atta', 'ata', 'maida', 'moyda', 'আটা', 'ময়দা'],
        'atta' => ['atta', 'ata', 'flour', 'আটা'],
        'ata' => ['atta', 'ata', 'flour', 'আটা'],
        'আটা' => ['atta', 'ata', 'flour', 'আটা'],
        'maida' => ['maida', 'moyda', 'ময়দা', 'ময়দা'],
        'moyda' => ['maida', 'moyda', 'ময়দা', 'ময়দা'],
        'ময়দা' => ['maida', 'moyda', 'ময়দা'],
        'suji' => ['suji', 'semolina', 'সুজি'],
        'সুজি' => ['suji', 'semolina', 'সুজি'],
        'honey' => ['honey', 'modhu', 'মধু'],
        'modhu' => ['honey', 'modhu', 'মধু'],
        'মধু' => ['honey', 'modhu', 'মধু'],

        // Spices (মসলা)
        'spices' => ['spices', 'spice', 'masala', 'moshla', 'মসলা', 'মশলা'],
        'masala' => ['masala', 'moshla', 'মসলা', 'মশলা'],
        'মসলা' => ['masala', 'spices', 'মসলা', 'মশলা'],
        'turmeric' => ['turmeric', 'holud', 'হলুদ'],
        'holud' => ['turmeric', 'holud', 'হলুদ'],
        'হলুদ' => ['turmeric', 'holud', 'হলুদ'],
        'coriander' => ['coriander', 'dhonia', 'dhoniya', 'ধনিয়া', 'ধনে'],
        'dhonia' => ['coriander', 'dhonia', 'ধনিয়া'],
        'ধনিয়া' => ['coriander', 'dhonia', 'ধনিয়া'],
        'cumin' => ['cumin', 'jira', 'জিরা'],
        'jira' => ['cumin', 'jira', 'জিরা'],
        'জিরা' => ['cumin', 'jira', 'জিরা'],

        // Snacks, Noodles, Drinks
        'noodles' => ['noodles', 'noodle', 'নুডুলস'],
        'নুডুলস' => ['noodles', 'noodle', 'নুডুলস'],
        'pasta' => ['pasta', 'macaroni', 'পাস্তা', 'ম্যাকারনি'],
        'chips' => ['chips', 'চিপস'],
        'চিপস' => ['chips', 'চিপস'],
        'chanachur' => ['chanachur', 'চানাচুর'],
        'চানাচুর' => ['chanachur', 'চানাচুর'],
        'muri' => ['muri', 'মুড়ি', 'মুড়ি'],
        'মুড়ি' => ['muri', 'মুড়ি'],
        'chira' => ['chira', 'চিড়া', 'চিড়া'],
        'চিড়া' => ['chira', 'চিড়া'],
        'water' => ['water', 'pani', 'পানি', 'মিনারেল'],
        'pani' => ['water', 'pani', 'পানি'],
        'পানি' => ['water', 'pani', 'পানি'],
        'juice' => ['juice', 'জুস'],
        'জুস' => ['juice', 'জুস'],
        'chocolate' => ['chocolate', 'চকলেট', 'ক্যান্ডি'],
        'চকলেট' => ['chocolate', 'চকলেট'],

        // Personal Care & Hygiene
        'soap' => ['soap', 'shaban', 'saban', 'সাবান'],
        'saban' => ['soap', 'shaban', 'saban', 'সাবান'],
        'shaban' => ['soap', 'shaban', 'saban', 'সাবান'],
        'সাবান' => ['soap', 'shaban', 'সাবান'],
        'shampoo' => ['shampoo', 'শ্যাম্পু'],
        'শ্যাম্পু' => ['shampoo', 'শ্যাম্পু'],
        'toothpaste' => ['toothpaste', 'টুথপেস্ট', 'পেস্ট', 'paste'],
        'টুথপেস্ট' => ['toothpaste', 'টুথপেস্ট'],
        'tissue' => ['tissue', 'টিস্যু'],
        'টিস্যু' => ['tissue', 'টিস্যু'],
        'diaper' => ['diaper', 'ডায়াপার', 'প্যাম্পার্স'],
        'ডায়াপার' => ['diaper', 'ডায়াপার'],
        'detergent' => ['detergent', 'হুইল', 'ডিটারজেন্ট', 'washing powder'],
        'coil' => ['coil', 'কয়েল', 'কয়েল'],
        'কয়েল' => ['coil', 'কয়েল', 'কয়েল'],
    ];

    /**
     * Parse query string and expand into token groups with bilingual synonyms.
     * Returns array of token groups: [ ['term1_alt1', 'term1_alt2', ...], ['term2_alt1', ...] ]
     */
    public static function expandQuery(string $input): array
    {
        $clean = trim(mb_strtolower($input));
        if ($clean === '') {
            return [];
        }

        // Split by whitespace
        $words = preg_split('/[\s\+\,\-\_]+/u', $clean, -1, PREG_SPLIT_NO_EMPTY);
        if (empty($words)) {
            return [];
        }

        $tokenGroups = [];

        foreach ($words as $w) {
            $w = trim($w);
            if (mb_strlen($w) < 1) {
                continue;
            }

            $alts = [$w];

            // 1. Direct synonym match
            if (isset(self::$synonyms[$w])) {
                $alts = array_merge($alts, self::$synonyms[$w]);
            } else {
                // Check if any synonym key is a substring or root
                foreach (self::$synonyms as $key => $values) {
                    if (mb_strpos($w, $key) !== false || mb_strpos($key, $w) !== false) {
                        $alts = array_merge($alts, $values);
                    }
                }
            }

            // 2. Simple character normalization (Bengali/English phonetic variants)
            $alts = array_merge($alts, self::phoneticVariants($w));

            $tokenGroups[] = array_values(array_unique(array_filter($alts, fn($t) => mb_strlen($t) >= 1)));
        }

        return $tokenGroups;
    }

    /**
     * Generate common Banglish/Bengali phonetic variations.
     */
    protected static function phoneticVariants(string $word): array
    {
        $variants = [];

        // English/Banglish common variations
        $banglishMap = [
            'sh' => ['s', 'sh'],
            'ee' => ['i', 'ee'],
            'oo' => ['u', 'oo'],
            'k'  => ['k', 'c', 'q'],
            'c'  => ['k', 'c'],
            'j'  => ['j', 'z'],
            'v'  => ['v', 'b', 'bh'],
            'b'  => ['b', 'v'],
        ];

        // Specific high-frequency grocery words
        $frequent = [
            'toast' => ['tost', 'toast', 'টোস্ট'],
            'tost' => ['toast', 'tost', 'টোস্ট'],
            'alu' => ['potato', 'alu', 'আলু'],
            'dim' => ['egg', 'eggs', 'dim', 'ডিম'],
            'tel' => ['oil', 'tel', 'তেল'],
            'dal' => ['lentil', 'dal', 'daal', 'ডাল'],
            'chal' => ['rice', 'chal', 'cal', 'চাল'],
            'cal' => ['rice', 'chal', 'cal', 'চাল'],
            'pran' => ['pran', 'praan', 'প্রাণ'],
            'praan' => ['pran', 'praan', 'প্রাণ'],
            'cha' => ['tea', 'cha', 'চা'],
            'cini' => ['sugar', 'chini', 'cini', 'চিনি'],
            'chini' => ['sugar', 'chini', 'cini', 'চিনি'],
            'lobon' => ['salt', 'lobon', 'লবণ'],
            'dudh' => ['milk', 'dudh', 'দুধ'],
            'mach' => ['fish', 'mach', 'মাছ'],
            'murgi' => ['chicken', 'murgi', 'মুরগি'],
            'peyaj' => ['onion', 'peyaj', 'পেঁয়াজ'],
            'roshun' => ['garlic', 'roshun', 'রসুন'],
            'ada' => ['ginger', 'ada', 'আদা'],
            'sobji' => ['vegetable', 'sobji', 'সবজি'],
            'biskut' => ['biscuit', 'biskut', 'বিস্কুট'],
        ];

        if (isset($frequent[$word])) {
            $variants = array_merge($variants, $frequent[$word]);
        }

        return $variants;
    }

    /**
     * Apply intelligent multi-word bilingual search to an Eloquent product query.
     */
    public static function applyProductSearch(Builder $query, string $rawSearch): Builder
    {
        $rawSearch = trim($rawSearch);
        if ($rawSearch === '') {
            return $query;
        }

        $tokenGroups = self::expandQuery($rawSearch);
        if (empty($tokenGroups)) {
            return $query;
        }

        // Ensure demo_products is joined for deep search across title, description & meta keywords
        self::ensureDemoProductJoined($query);

        // All token groups must match (AND condition between groups, OR between synonyms)
        $query->where(function (Builder $master) use ($tokenGroups) {
            foreach ($tokenGroups as $group) {
                $master->where(function (Builder $sub) use ($group) {
                    foreach ($group as $alt) {
                        $isAscii = preg_match('/^[a-zA-Z0-9\s\-\_\.\(\)]+$/', $alt);

                        $sub->orWhere('products.name', 'like', "%{$alt}%")
                            ->orWhere('products.description', 'like', "%{$alt}%")
                            ->orWhere('demo_products.name', 'like', "%{$alt}%")
                            ->orWhere('demo_products.description', 'like', "%{$alt}%");

                        if ($isAscii) {
                            $sub->orWhere('products.slug', 'like', "%{$alt}%");
                        }
                    }
                });
            }
        });

        // Relevance ordering
        $firstWord = $tokenGroups[0][0] ?? $rawSearch;
        $query->orderByRaw("
            CASE 
                WHEN products.name LIKE ? THEN 100
                WHEN products.name LIKE ? THEN 80
                WHEN demo_products.name LIKE ? THEN 60
                WHEN products.name LIKE ? THEN 40
                ELSE 0 
            END DESC
        ", [
            "%{$rawSearch}%",
            "{$rawSearch}%",
            "%{$rawSearch}%",
            "%{$firstWord}%"
        ]);

        return $query;
    }

    /**
     * Relaxed OR-based search across token groups with match ranking.
     */
    public static function applyRelaxedSearch(Builder $query, string $rawSearch): Builder
    {
        $rawSearch = trim($rawSearch);
        if ($rawSearch === '') {
            return $query;
        }

        $tokenGroups = self::expandQuery($rawSearch);
        if (empty($tokenGroups)) {
            return $query;
        }

        self::ensureDemoProductJoined($query);

        // Match ANY token group
        $query->where(function (Builder $master) use ($tokenGroups) {
            foreach ($tokenGroups as $group) {
                $master->orWhere(function (Builder $sub) use ($group) {
                    foreach ($group as $alt) {
                        $isAscii = preg_match('/^[a-zA-Z0-9\s\-\_\.\(\)]+$/', $alt);

                        $sub->orWhere('products.name', 'like', "%{$alt}%")
                            ->orWhere('products.description', 'like', "%{$alt}%")
                            ->orWhere('demo_products.name', 'like', "%{$alt}%")
                            ->orWhere('demo_products.description', 'like', "%{$alt}%");

                        if ($isAscii) {
                            $sub->orWhere('products.slug', 'like', "%{$alt}%");
                        }
                    }
                });
            }
        });

        // Relevance ordering
        $firstWord = $tokenGroups[0][0] ?? $rawSearch;
        $query->orderByRaw("
            CASE 
                WHEN products.name LIKE ? THEN 100
                WHEN products.name LIKE ? THEN 80
                WHEN demo_products.name LIKE ? THEN 60
                WHEN products.name LIKE ? THEN 40
                ELSE 0 
            END DESC
        ", [
            "%{$rawSearch}%",
            "{$rawSearch}%",
            "%{$rawSearch}%",
            "%{$firstWord}%"
        ]);

        return $query;
    }

    /**
     * Ensure demo_products table is joined without duplicate joins.
     */
    protected static function ensureDemoProductJoined(Builder $query): void
    {
        $joins = $query->getQuery()->joins ?? [];
        foreach ($joins as $join) {
            if ($join->table === 'demo_products' || str_contains($join->table, 'demo_products')) {
                return;
            }
        }
        $query->leftJoin('demo_products', 'products.demo_id', '=', 'demo_products.id');
    }
}
