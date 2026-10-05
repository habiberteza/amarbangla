<?php

namespace App\Libraries;

class ProductImageHelper
{
    /**
     * Curated high quality product images for diverse categories and products.
     */
    protected static array $keywordImages = [
        // Vehicles & Transport
        'ambulance'   => 'https://images.unsplash.com/photo-1587745416684-47953f16f02f?w=300&q=80',
        'এম্বুলেন্স'  => 'https://images.unsplash.com/photo-1587745416684-47953f16f02f?w=300&q=80',
        'truck'       => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=300&q=80',
        'ট্রাক'       => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=300&q=80',
        'pick up'     => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=300&q=80',
        'pickup'      => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=300&q=80',
        'পিকআপ'       => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=300&q=80',
        'riksha'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=300&q=80',
        'rickshaw'    => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=300&q=80',
        'রিকশা'       => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=300&q=80',
        'noah'        => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'hiace'       => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'micro bus'   => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'microbus'    => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'মাইক্রোবাস'  => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'corolla'     => 'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=300&q=80',
        'allion'      => 'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=300&q=80',
        'premio'      => 'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=300&q=80',
        'car'         => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',
        'কার'         => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80',

        // Gas & Energy
        'lpg'         => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80',
        'gas'         => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80',
        'cylinder'    => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80',
        'গ্যাস'       => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80',
        'সিলিন্ডার'   => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80',

        // Bakery & Cakes
        'vanilla'     => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',
        'chocolate 1nx' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',
        'chocolate 2nx' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',
        'cake'        => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',
        'কেক'         => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',
        'pound'       => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80',

        // Birds & Pet Food
        'pakhir'      => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=300&q=80',
        'পাখির খাবার' => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=300&q=80',
        'তিশি'        => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'tishi'       => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'সূর্যমুখী'    => 'https://images.unsplash.com/photo-1597848212624-a19eb35e2651?w=300&q=80',
        'surjomukhi'  => 'https://images.unsplash.com/photo-1597848212624-a19eb35e2651?w=300&q=80',
        'কাউন'        => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'ক্যাপসুল'     => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&q=80',

        // Ramadan / Traditional food
        'গুড়'         => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80',
        'jaggery'     => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80',
        'মুড়ি'        => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'চিড়ে'        => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'খেজুর'       => 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=300&q=80',
        'dates'       => 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=300&q=80',
        'চিনিগুড়া'   => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'চিনি '       => 'https://images.unsplash.com/photo-1587735243615-c03f25aaff15?w=300&q=80',
        'sugar'       => 'https://images.unsplash.com/photo-1587735243615-c03f25aaff15?w=300&q=80',
        'রুহ আফজা'    => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=300&q=80',
        'বেশন'        => 'https://images.unsplash.com/photo-1514733670139-4d87a1941d55?w=300&q=80',
        'ছোলা'        => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?w=300&q=80',
        'বুট'         => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?w=300&q=80',

        // Technology & Telecom
        'internet'    => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&q=80',
        'mbps'        => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&q=80',
        'router'      => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&q=80',
        'mobile'      => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'phone'       => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'vivo'        => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'samsung'     => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=300&q=80',
        'oppo'        => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'xiaomi'      => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'realme'      => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'মোবাইল'      => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80',
        'watch'       => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=300&q=80',
        'ঘড়ি'         => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=300&q=80',

        // Cleaning & Personal Care
        'surf excel'  => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80',
        'washing powder' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80',
        'হুইল'        => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80',
        'সার্ফ এক্সেল' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80',
        'detergent'   => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80',
        'goodrej'     => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80',
        'hair color'  => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80',
        'soap'        => 'https://images.unsplash.com/photo-1607006314144-8d4e92eb0e57?w=300&q=80',
        'সাবান'       => 'https://images.unsplash.com/photo-1607006314144-8d4e92eb0e57?w=300&q=80',
        'shampoo'     => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=300&q=80',
        'শ্যাম্পু'    => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=300&q=80',
        'handwash'    => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&q=80',
        'হ্যান্ডওয়াশ' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&q=80',
        'diaper'      => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80',
        'ডায়াপার'    => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80',
        'toothpaste'  => 'https://images.unsplash.com/photo-1559591937-e1032b490f23?w=300&q=80',
        'টুথপেস্ট'    => 'https://images.unsplash.com/photo-1559591937-e1032b490f23?w=300&q=80',

        // Fresh Vegetables
        'শিম'         => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80',
        'ওলকপি'       => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80',
        'পালং শাক'    => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=300&q=80',
        'শাক'         => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=300&q=80',
        'সবজি'        => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80',
        'vegetable'   => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80',
        'আলু'         => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=300&q=80',
        'potato'      => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=300&q=80',
        'onion'       => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&q=80',
        'পেঁয়াজ'      => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&q=80',
        'পিঁয়াজ'      => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&q=80',
        'রসুন'        => 'https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?w=300&q=80',
        'garlic'      => 'https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?w=300&q=80',
        'আদা'         => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=300&q=80',
        'ginger'      => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=300&q=80',
        'টমেটো'       => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=300&q=80',
        'tomato'      => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=300&q=80',
        'বেগুন'       => 'https://images.unsplash.com/photo-1528825871115-3581a5387919?w=300&q=80',
        'eggplant'    => 'https://images.unsplash.com/photo-1528825871115-3581a5387919?w=300&q=80',

        // Food & Staples
        'oil'         => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&q=80',
        'তেল'         => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&q=80',
        'সয়াবিন'      => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&q=80',
        'সরিষা'       => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&q=80',
        'ghee'        => 'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=300&q=80',
        'ঘি'          => 'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=300&q=80',
        'rice'        => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'চাল'         => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'নাজিরশাইল'   => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'মিনিকেট'     => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'বাসমতী'      => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'পোলাও'       => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80',
        'ডাল'         => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?w=300&q=80',
        'মশুর'        => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?w=300&q=80',
        'আটা'         => 'https://images.unsplash.com/photo-1514733670139-4d87a1941d55?w=300&q=80',
        'ময়দা'        => 'https://images.unsplash.com/photo-1514733670139-4d87a1941d55?w=300&q=80',
        'লবণ'         => 'https://images.unsplash.com/photo-1587735243615-c03f25aaff15?w=300&q=80',
        'spice'       => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&q=80',
        'মশলা'        => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&q=80',
        'হলুদ'        => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=300&q=80',
        'মরিচ'        => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=300&q=80',
        'জিরা'        => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&q=80',
        'দারুচিনি'    => 'https://images.unsplash.com/photo-1509358271058-acd22cc93898?w=300&q=80',
        'এলাচ'        => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&q=80',
        'চা '         => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=300&q=80',
        'চা পাতা'     => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=300&q=80',
        'tea'         => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=300&q=80',
        'noodles'     => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=300&q=80',
        'নুডুলস'      => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=300&q=80',
        'biscuit'     => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300&q=80',
        'বিস্কুট'     => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300&q=80',
        'চকলেট'       => 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=300&q=80',
        'chocolate'   => 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=300&q=80',
        'drink'       => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300&q=80',
        'juice'       => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=300&q=80',
        'জোস'         => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=300&q=80',
        'milk'        => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&q=80',
        'দুধ'         => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&q=80',
        'fish'        => 'https://images.unsplash.com/photo-1534948216015-843149f72be3?w=300&q=80',
        'মাছ'         => 'https://images.unsplash.com/photo-1534948216015-843149f72be3?w=300&q=80',
        'meat'        => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80',
        'মাংস'        => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80',
        'egg'         => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=300&q=80',
        'ডিম'         => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=300&q=80',
    ];

    /**
     * Category ID fallback images
     */
    protected static array $categoryImages = [
        1   => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80', // Smart Phone
        2   => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80', // Mobile
        4   => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80', // Groceries
        5   => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&q=80', // Rice
        7   => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&q=80', // Spice
        9   => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&q=80', // Oil & Ghee
        14  => 'https://images.unsplash.com/photo-1607006314144-8d4e92eb0e57?w=300&q=80', // Skin Care
        17  => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&q=80', // Bread & Bakery
        22  => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300&q=80', // Cookies
        26  => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=300&q=80', // Snacks
        28  => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=300&q=80', // Noodles
        30  => 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=300&q=80', // Chocolate & Candy
        36  => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300&q=80', // Beverage
        42  => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&q=80', // Dairy
        46  => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80', // Meat & Fish
        49  => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=300&q=80', // Vegetables
        52  => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80', // Detergent & Cleaning
        53  => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80', // Personal Care
        54  => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80', // Hair Color
        55  => 'https://images.unsplash.com/photo-1559591937-e1032b490f23?w=300&q=80', // Oral Care
        56  => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&q=80', // Handwash
        58  => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80', // Baby Care
        60  => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80', // Diapers & Wipes
        65  => 'https://images.unsplash.com/photo-1607006314144-8d4e92eb0e57?w=300&q=80', // Bath & Skincare
        68  => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80', // Fresh Vegetables
        69  => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=300&q=80', // Fruits
        74  => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=300&q=80', // Tea
        76  => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=300&q=80', // Juice
        77  => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300&q=80', // Soft Drinks
        79  => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=300&q=80', // Syrups & Drinks
        96  => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300&q=80', // Biscuits
        103 => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80', // Home & Cleaning
        106 => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80', // Cleaning Supplies
        108 => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80', // Laundry
        135 => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80', // Keto Food
        141 => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=300&q=80', // Shampoo
        153 => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=300&q=80', // Popular / Cleaning
        164 => 'https://images.unsplash.com/photo-1627483262268-9c2b5b2834b5?w=300&q=80', // LPG Gas
        217 => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=300&q=80', // Watch
        238 => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80', // Chocolate Cake
        239 => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&q=80', // Vanilla Cake
        246 => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=300&q=80', // Pet & Bird Food
        247 => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&q=80', // Internet Line
        248 => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&q=80', // Broadband Mbps
        249 => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80', // Ramadan Food
        252 => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80', // Rent a Car
        253 => 'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=300&q=80', // Car
        254 => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=300&q=80', // Microbus
        255 => 'https://images.unsplash.com/photo-1587745416684-47953f16f02f?w=300&q=80', // Ambulance
        256 => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=300&q=80', // Pickup
        257 => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=300&q=80', // Truck
        258 => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=300&q=80', // Riksha
    ];

    public static function getSmartImageUrl(string $name = '', ?int $catId = null): string
    {
        $lowerName = mb_strtolower(trim($name), 'UTF-8');

        // 1. Search keyword match
        foreach (self::$keywordImages as $kw => $url) {
            if (mb_stripos($lowerName, $kw, 0, 'UTF-8') !== false) {
                return $url;
            }
        }

        // 2. Search category match
        if ($catId !== null && isset(self::$categoryImages[$catId])) {
            return self::$categoryImages[$catId];
        }

        // 3. Fallback generic grocery/product image
        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
    }

    /**
     * Seeds local images for a product ID into public/uploads/demo_product_image/{id}/
     */
    public static function seedProductImage(object|array $prod): bool
    {
        $id = is_object($prod) ? ($prod->id ?? null) : ($prod['id'] ?? null);
        $name = is_object($prod) ? ($prod->name ?? '') : ($prod['name'] ?? '');
        $catId = is_object($prod) ? ($prod->prod_cat_id ?? null) : ($prod['prod_cat_id'] ?? null);
        $picture = is_object($prod) ? ($prod->picture ?? null) : ($prod['picture'] ?? null);

        if (!$id) {
            return false;
        }

        $baseDir = function_exists('public_path')
            ? public_path('uploads/demo_product_image')
            : (defined('FCPATH') ? rtrim(FCPATH, '/\\') . '/uploads/demo_product_image' : __DIR__ . '/../../public/uploads/demo_product_image');

        $destDir = "{$baseDir}/{$id}";
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }

        $imgName = "product_{$id}.jpg";
        if (!empty($picture)) {
            $decoded = json_decode(html_entity_decode((string)$picture), true);
            if (!empty($decoded) && is_array($decoded)) {
                $first = reset($decoded);
                if (!empty($first) && is_string($first)) {
                    $imgName = $first;
                }
            }
        }

        $fullPath = "{$destDir}/{$imgName}";
        $thumbPath = "{$destDir}/70_{$imgName}";

        // If both already exist on disk and have content, skip
        if (file_exists($fullPath) && filesize($fullPath) > 500 && file_exists($thumbPath) && filesize($thumbPath) > 500) {
            return true;
        }

        // Download from smart image URL
        $sourceUrl = self::getSmartImageUrl($name, $catId ? (int)$catId : null);
        
        $imgData = self::fetchUrlContent($sourceUrl);
        if (!$imgData || strlen($imgData) < 500) {
            return false;
        }

        @file_put_contents($fullPath, $imgData);
        @file_put_contents($thumbPath, $imgData);

        return true;
    }

    protected static function fetchUrlContent(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && $data && strlen($data) > 500) {
            return $data;
        }
        return null;
    }
}
