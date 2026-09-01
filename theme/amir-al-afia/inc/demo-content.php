<?php
/**
 * One-click starter content.
 *
 * Sideloads photography into the Media Library, then creates the demo
 * properties, agents and attraction pages so a fresh install has something to
 * render. Everything it creates is ordinary content the office can edit or
 * delete.
 *
 * Images are pulled once and stored locally, so the published site never
 * hotlinks a third-party CDN.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a Wikimedia Commons file URL, scaled server-side.
 *
 * @param string $file Commons file name, without the "File:" prefix.
 * @param int    $width Target width. 2000 so that even a wide panorama still
 *                      clears the 630px height the Open Graph crop needs.
 */
function aaa_commons_url( string $file, int $width = 2000 ): string {
	return 'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode( $file ) . '?width=' . $width;
}

/**
 * The photo set.
 *
 * Every photograph here is of Oman, and every one was opened and checked to be
 * the place it is captioned as. That is not a given: the original mockup's
 * picks included the Great Sphinx captioned as an Omani house and Monument
 * Valley captioned as Omani desert.
 *
 * The building photography is interim. It is real Omani architecture rather
 * than generic villa stock, so the site reads as an Omani agency until the
 * office uploads its own listing photos — which is the point at which all of
 * this should be deleted.
 *
 * Licences are recorded per image and stored on the attachment, because CC BY
 * and CC BY-SA both require the credit to travel with the photo.
 *
 * @return array<string, array<string, string>>
 */
function aaa_demo_images(): array {
	$commons = 'https://commons.wikimedia.org/wiki/File:';

	return array(

		// --- Omani buildings and residential areas ----------------------
		'om-khuwair'      => array(
			'url'     => aaa_commons_url( 'Al-Khuwair, Muscat.jpg' ),
			'alt'     => 'White low-rise residential buildings in Al Khuwair, Muscat, below the Al Hajar mountains',
			'author'  => 'Joe Castleman',
			'license' => 'CC BY-SA 3.0',
			'source'  => $commons . 'Al-Khuwair,_Muscat.jpg',
		),
		'om-almouj-night' => array(
			'url'     => aaa_commons_url( 'Al Mouj Marina.jpg' ),
			'alt'     => 'Apartment buildings lit up at night around Al Mouj Marina, Muscat',
			'author'  => 'Karananand2',
			'license' => 'CC0',
			'source'  => $commons . 'Al_Mouj_Marina.jpg',
		),
		'om-almouj-boats' => array(
			'url'     => aaa_commons_url( 'Al-mouj-marina-3.jpg' ),
			'alt'     => 'Yachts moored at Al Mouj Marina with waterfront buildings behind',
			'author'  => 'A1000',
			'license' => 'CC0',
			'source'  => $commons . 'Al-mouj-marina-3.jpg',
		),
		'om-almouj-front' => array(
			'url'     => aaa_commons_url( 'Al-mouj-marina.jpg' ),
			'alt'     => 'The marina building and waterfront promenade at Al Mouj, Muscat',
			'author'  => 'A1000',
			'license' => 'CC0',
			'source'  => $commons . 'Al-mouj-marina.jpg',
		),
		'om-house'        => array(
			'url'     => aaa_commons_url( 'Muscat Oman normal house.jpg' ),
			'alt'     => 'A residential street of Omani villas in Muscat with a rocky hillside behind',
			'author'  => 'Yourusernamewillbepublic2',
			'license' => 'CC0',
			'source'  => $commons . 'Muscat_Oman_normal_house.jpg',
		),
		'om-aerial'       => array(
			'url'     => aaa_commons_url( 'Aerial view of Muscat, Oman (53698188165).jpg' ),
			'alt'     => 'Aerial view over the low white housing of Muscat',
			'author'  => 'dronepicr',
			'license' => 'CC BY 2.0',
			'source'  => $commons . 'Aerial_view_of_Muscat,_Oman_(53698188165).jpg',
		),
		'om-coast-res'    => array(
			'url'     => aaa_commons_url( 'Muscat 2016 1.jpg' ),
			'alt'     => 'Aerial view of a Muscat residential district along the coastline',
			'author'  => 'Arne Müseler',
			'license' => 'CC BY-SA 3.0 DE',
			'source'  => $commons . 'Muscat_2016_1.jpg',
		),
		'om-development'  => array(
			'url'     => aaa_commons_url( 'Muscat 2016 4.jpg' ),
			'alt'     => 'Aerial view of a planned coastal residential development near Muscat',
			'author'  => 'Arne Müseler',
			'license' => 'CC BY-SA 3.0 DE',
			'source'  => $commons . 'Muscat_2016_4.jpg',
		),
		'om-marina-air'   => array(
			'url'     => aaa_commons_url( 'Muttrah-Muscat مطرح، مسقط 05.jpg' ),
			'alt'     => 'Aerial view of a marina development on the Muscat coast',
			'author'  => 'Mostafameraji',
			'license' => 'CC BY-SA 4.0',
			'source'  => $commons . 'Muttrah-Muscat_%D9%85%D8%B7%D8%B1%D8%AD%D8%8C_%D9%85%D8%B3%D9%82%D8%B7_05.jpg',
		),
		'om-office'       => array(
			'url'     => aaa_commons_url( 'Ahlibank Head Office .jpg' ),
			'alt'     => 'A modern office building in Muscat framed by palm trees',
			'author'  => 'Wael Mohammed Al-Masri',
			'license' => 'CC BY-SA 4.0',
			'source'  => $commons . 'Ahlibank_Head_Office_.jpg',
		),

		// --- Places, for the Oman guide --------------------------------
		'om-corniche'     => array(
			'url'     => aaa_commons_url( 'Aerial view of the coastline of Muttrah.jpg' ),
			'alt'     => 'The Muttrah Corniche curving along Muscat harbour beneath the Al Hajar mountains',
			'author'  => 'Izeberg007',
			'license' => 'CC0',
			'source'  => $commons . 'Aerial_view_of_the_coastline_of_Muttrah.jpg',
		),
		'om-maritime'     => array(
			'url'     => aaa_commons_url( 'Maritime Majesty - Flickr - Abubakr Saeed.jpg' ),
			'alt'     => 'Muttrah harbour at sunset with ships at anchor and the mountains behind',
			'author'  => 'Abubakr Saeed',
			'license' => 'CC BY 4.0',
			'source'  => $commons . 'Maritime_Majesty_-_Flickr_-_Abubakr_Saeed.jpg',
		),
		'om-houses'       => array(
			'url'     => aaa_commons_url( 'Traditional Architecture Muttrah.jpg' ),
			'alt'     => 'Carved wooden balconies on traditional whitewashed merchant houses in Muttrah',
			'author'  => 'Izeberg007',
			'license' => 'CC0',
			'source'  => $commons . 'Traditional_Architecture_Muttrah.jpg',
		),
		'om-mosque'       => array(
			'url'     => 'https://pd.w.org/2022/08/506630598190f7316.03658406-2048x1359.jpeg',
			'alt'     => 'The arcaded courtyard and minaret of the Sultan Qaboos Grand Mosque, Muscat',
			'author'  => 'WordPress Photo Directory',
			'license' => 'CC0',
			'source'  => 'https://wordpress.org/photos/photo/506630598190f7316/',
		),
		'om-desert'       => array(
			'url'     => aaa_commons_url( 'Wahiba Sands, Oman (Unsplash).jpg' ),
			'alt'     => 'A desert camp under the Milky Way in the Wahiba Sands',
			'author'  => 'Freddie Marriage',
			'license' => 'CC0',
			'source'  => $commons . 'Wahiba_Sands,_Oman_(Unsplash).jpg',
		),
		'om-wadi'         => array(
			'url'     => aaa_commons_url( 'Wadi Shab 11-2025.jpg' ),
			'alt'     => 'Turquoise pools between the canyon walls of Wadi Shab',
			'author'  => 'Izeberg007',
			'license' => 'CC0',
			'source'  => $commons . 'Wadi_Shab_11-2025.jpg',
		),
		'om-mountain'     => array(
			'url'     => aaa_commons_url( 'Jebel Shams, Jabal Shams, Oman (Unsplash).jpg' ),
			'alt'     => 'Two walkers on a ridge looking across the Jebel Shams range',
			'author'  => 'Freddie Marriage',
			'license' => 'CC0',
			'source'  => $commons . 'Jebel_Shams,_Jabal_Shams,_Oman_(Unsplash).jpg',
		),
		'om-khayran'      => array(
			'url'     => aaa_commons_url( 'Bandar Khayran, Muscat, Sultanate of Oman.jpg' ),
			'alt'     => 'Turquoise coves and limestone inlets at Bandar Khayran near Muscat',
			'author'  => 'Erfan.arafat',
			'license' => 'CC BY-SA 4.0',
			'source'  => $commons . 'Bandar_Khayran,_Muscat,_Sultanate_of_Oman.jpg',
		),
	);
}

/**
 * The demo property listings.
 *
 * @return array<int, array<string, mixed>>
 */
function aaa_demo_properties(): array {
	return array(
		array(
			'title'   => 'Al Mouj Marina Apartment',
			'image'   => 'om-almouj-night',
			'price'   => 285000,
			'beds'    => '2',
			'baths'   => '2',
			'area'    => 1200,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-sale',
			'excerpt' => 'A bright two-bedroom apartment a short walk from the marina boardwalk, with a west-facing balcony over the water.',
		),
		array(
			'title'   => 'Marina View Residence',
			'image'   => 'om-almouj-boats',
			'price'   => 400000,
			'beds'    => '3',
			'baths'   => '2',
			'area'    => 1300,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-sale',
			'excerpt' => 'Corner residence on an upper floor with dual-aspect glazing, a private lift lobby and two parking bays.',
		),
		array(
			'title'   => 'Luxury Villa — Qurum',
			'image'   => 'om-house',
			'price'   => 1250000,
			'beds'    => '6',
			'baths'   => '6',
			'area'    => 6700,
			'address' => 'Qurum, Muscat',
			'type'    => 'luxury',
			'deal'    => 'for-sale',
			'excerpt' => 'A six-bedroom family villa on a mature plot in Qurum, with a pool, a majlis and staff quarters.',
		),
		array(
			'title'   => 'Studio Apartment — Al Mouj',
			'image'   => 'om-almouj-front',
			'price'   => 9600,
			'beds'    => 'Studio',
			'baths'   => '1',
			'area'    => 620,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'Furnished studio let annually, service charges included, with access to the pool and gym.',
		),
		array(
			'title'   => 'Garden Villa — Madinat Sultan Qaboos',
			'image'   => 'om-khuwair',
			'price'   => 32000,
			'beds'    => '4',
			'baths'   => '4',
			'area'    => 3800,
			'address' => 'Madinat Sultan Qaboos, Muscat',
			'type'    => 'villa',
			'deal'    => 'for-rent',
			'excerpt' => 'Four-bedroom villa with a walled garden, annual rent, available for immediate occupation.',
		),
		array(
			'title'   => 'Sea-Facing Penthouse — Shatti Al Qurum',
			'image'   => 'om-coast-res',
			'price'   => 890000,
			'beds'    => '4',
			'baths'   => '4',
			'area'    => 3100,
			'address' => 'Shatti Al Qurum, Muscat',
			'type'    => 'luxury',
			'deal'    => 'for-sale',
			'excerpt' => 'Full-floor penthouse with a wraparound terrace looking north over the Gulf of Oman.',
		),
		array(
			'title'   => 'Family Villa — Azaiba',
			'image'   => 'om-aerial',
			'price'   => 465000,
			'beds'    => '5',
			'baths'   => '5',
			'area'    => 4200,
			'address' => 'Azaiba, Muscat',
			'type'    => 'villa',
			'deal'    => 'for-sale',
			'excerpt' => 'Five-bedroom villa with a pool and covered parking, ten minutes from the airport.',
		),
		array(
			'title'   => 'Two-Bedroom Apartment — Ghubrah',
			'image'   => 'om-development',
			'price'   => 7200,
			'beds'    => '2',
			'baths'   => '2',
			'area'    => 1050,
			'address' => 'Ghubrah, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'Well-kept apartment let annually in a quiet building with a lift and allocated parking.',
		),
		array(
			'title'   => 'Townhouse — Muttrah',
			'image'   => 'om-houses',
			'price'   => 198000,
			'beds'    => '3',
			'baths'   => '2',
			'area'    => 1650,
			'address' => 'Muttrah, Muscat',
			'type'    => 'villa',
			'deal'    => 'for-sale',
			'excerpt' => 'A restored townhouse in the old quarter, carved balcony intact, two minutes from the corniche.',
		),
		array(
			'title'   => 'Waterfront Apartment — Muttrah Corniche',
			'image'   => 'om-corniche',
			'price'   => 11400,
			'beds'    => '2',
			'baths'   => '2',
			'area'    => 1150,
			'address' => 'Muttrah, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'Second-floor apartment facing the harbour, let annually, with a balcony over the promenade.',
		),
		array(
			'title'   => 'Office Floor — Al Khuwair',
			'image'   => 'om-office',
			'price'   => 28000,
			'beds'    => 'Open plan',
			'baths'   => '2',
			'area'    => 2400,
			'address' => 'Al Khuwair, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'A full floor on the business strip, fitted and cabled, with dedicated parking for eight.',
		),
		array(
			'title'   => 'Marina Penthouse — Al Mouj',
			'image'   => 'om-marina-air',
			'price'   => 1450000,
			'beds'    => '4',
			'baths'   => '5',
			'area'    => 4600,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'luxury',
			'deal'    => 'for-sale',
			'excerpt' => 'Top-floor penthouse over the marina, private roof terrace and plunge pool, berth negotiable.',
		),
	);
}

/**
 * The six places in the Oman guide, each with a real page behind it.
 *
 * Figures are the widely published ones and are deliberately hedged where a
 * source would vary; the office should confirm anything it wants to state as
 * fact in marketing.
 *
 * @return array<int, array<string, mixed>>
 */
function aaa_demo_attractions(): array {
	return array(
		array(
			'title'   => 'Muttrah Corniche',
			'image'   => 'om-corniche',
			'region'  => 'Muscat Governorate',
			'drive'   => 'In Muscat',
			'best'    => 'October to April',
			'areas'   => 'Muttrah, Ruwi, Darsait',
			'lat'     => '23.6187',
			'lng'     => '58.5650',
			'excerpt' => 'The waterfront promenade along Muscat\'s old harbour, with the souq at one end and a Portuguese-era fort on the ridge above.',
			'content' => '<p>The Corniche runs for roughly three kilometres along Muttrah\'s natural harbour, which is the reason Muscat exists where it does. Merchant houses face the water on one side and dhows and cruise ships sit on the other. It is the part of the capital that still looks like the trading port it was.</p>
<p>At the southern end is <strong>Muttrah Souq</strong>, one of the oldest working markets in the Arab world — frankincense, silver, textiles and a great deal of everything else, under a covered timber roof. Above the harbour sits <strong>Muttrah Fort</strong>, built by the Portuguese in the 1580s during their occupation of the coast.</p>
<h2>What it is like to live near</h2>
<p>Muttrah itself is dense, old and characterful, with limited modern housing stock. Most people who want to be near it live in <strong>Ruwi</strong> — the commercial district immediately inland, well supplied with apartments — or in <strong>Darsait</strong>. Both are among the more affordable parts of the capital, and both are a short drive from the corniche.</p>
<p>The trade-off is traffic and age of building. If you want new construction with parking and a pool, you will be looking further west along the coast.</p>',
		),
		array(
			'title'   => 'Traditional Houses of Muttrah',
			'image'   => 'om-houses',
			'region'  => 'Muscat Governorate',
			'drive'   => 'In Muscat',
			'best'    => 'Year round',
			'areas'   => 'Muttrah, Ruwi',
			'lat'     => '23.6205',
			'lng'     => '58.5638',
			'excerpt' => 'Whitewashed merchant houses with carved wooden balconies — the domestic architecture of Oman\'s trading century.',
			'content' => '<p>The houses along the Muttrah waterfront are the clearest surviving example of Omani merchant architecture. Thick whitewashed walls, small deep-set windows and, above the street, carved wooden balconies screened with lattice — the arrangement that lets air move through a room while keeping the sun and the street out.</p>
<p>The woodwork reflects where Oman\'s merchants sailed. Omani trade reached the Indian coast, the Gulf and Zanzibar, and the carving, joinery and painted detail carry all three influences. Doors were often the most expensive element of a house, and many were made in India to Omani order.</p>
<h2>Why it matters if you are buying</h2>
<p>Very little of this stock trades on the open market, and what does usually needs structural work and sits in a conservation context with real constraints. It is worth understanding anyway: the vocabulary — the <em>mashrabiya</em> screen, the shaded courtyard, the thick wall — reappears in a lot of contemporary Omani villa design, and knowing the original tells you which modern versions are doing it properly.</p>',
		),
		array(
			'title'   => 'Sultan Qaboos Grand Mosque',
			'image'   => 'om-mosque',
			'region'  => 'Bawshar, Muscat Governorate',
			'drive'   => 'About 15 minutes from the city centre',
			'best'    => 'Saturday to Thursday, early morning',
			'areas'   => 'Ghubrah, Bawshar, Al Khuwair, Azaiba',
			'lat'     => '23.5843',
			'lng'     => '58.3887',
			'excerpt' => 'Oman\'s principal mosque, opened in 2001, and one of the few in the country that welcomes non-Muslim visitors.',
			'content' => '<p>The mosque was commissioned by Sultan Qaboos bin Said and opened in 2001 after about six years of construction. The complex accommodates roughly 20,000 worshippers across the main prayer hall, the women\'s hall and the courtyards, and it is the largest mosque in the country.</p>
<p>Two things inside are usually singled out. The main prayer hall carpet covers over 4,000 square metres and was hand-woven in Iran in a single piece — for some years it was the largest of its kind in the world. Above it hangs a Swarovski crystal chandelier standing some fourteen metres tall.</p>
<p>The building is worth seeing for the stonework as much as the set pieces: Indian sandstone, a restrained palette, and a courtyard arcade that is very carefully proportioned.</p>
<h2>Visiting</h2>
<p>Non-Muslim visitors are welcome outside prayer times, typically <strong>Saturday to Thursday, early morning</strong>. Dress is covered arms and legs, and women should cover their hair. Confirm current hours before travelling — they change around Ramadan and public holidays.</p>
<h2>Living nearby</h2>
<p>The mosque sits in <strong>Bawshar</strong>, on the edge of the band of western suburbs where most of Muscat\'s newer housing is. <strong>Al Khuwair</strong> and <strong>Ghubrah</strong> are the established apartment districts either side of it, close to embassies, schools and the main highway; <strong>Azaiba</strong> runs down to the beach and is quieter and more villa-led.</p>',
		),
		array(
			'title'   => 'Wahiba Sands',
			'image'   => 'om-desert',
			'region'  => 'Ash Sharqiyah',
			'drive'   => 'About 2 to 3 hours',
			'best'    => 'October to March',
			'areas'   => 'Bidiyah, Ibra',
			'lat'     => '21.9167',
			'lng'     => '58.7500',
			'excerpt' => 'A dune sea roughly 180 km long, reachable from Muscat in a morning, and the usual answer to "where do people go for the weekend?"',
			'content' => '<p>Properly the Sharqiyah Sands, and still widely called Wahiba after the tribe whose territory it is. The dune field runs roughly 180 kilometres north to south and around 80 across, with dunes reaching something like 100 metres in the higher parts. The sand shifts from pale gold to a deep rust depending on where you are and how the light falls.</p>
<p>Most visitors drive down for a night at one of the desert camps near <strong>Bidiyah</strong>, where the road ends and the sand begins. Tyres come down, a guide takes over, and you spend the afternoon on the dunes and the evening under a sky with effectively no light pollution in it.</p>
<h2>Practicalities</h2>
<p>You need a proper four-wheel drive and, unless you know the terrain, a guide — camps will normally meet you at the edge and lead you in. Go between <strong>October and March</strong>; summer daytime temperatures make it genuinely unpleasant and, in the wrong circumstances, dangerous.</p>
<h2>Why it appears on a property site</h2>
<p>Because it is two to three hours from the capital, and that shapes how people use a home in Muscat. A weekend place in the interior is a normal thing to own here, and buyers regularly ask how far the desert, the mountains and the coast are before they ask about the kitchen.</p>',
		),
		array(
			'title'   => 'Wadi Shab',
			'image'   => 'om-wadi',
			'region'  => 'Ash Sharqiyah North',
			'drive'   => 'About 2 hours',
			'best'    => 'October to April',
			'areas'   => 'Tiwi, Sur, Quriyat',
			'lat'     => '22.8406',
			'lng'     => '59.2431',
			'excerpt' => 'A canyon of turquoise pools an easy drive down the coast, ending in a waterfall you can only reach by swimming.',
			'content' => '<p>Wadi Shab opens off the coast road near the village of <strong>Tiwi</strong>, between Quriyat and Sur. You leave the car at the mouth, take a short boat across the inlet, and then walk — around forty-five minutes over rock and gravel, past date palms and terraced smallholdings, with the canyon walls narrowing above you.</p>
<p>The walk ends at a chain of clear green pools. From the last one, a narrow slot in the rock leads into a partly enclosed chamber with a waterfall falling into it. You have to swim the final stretch, and the gap is tight enough that anything you are carrying needs to be waterproof or left behind.</p>
<h2>Practicalities</h2>
<p>Go <strong>October to April</strong>. Take water and shoes you can walk and swim in. Avoid it entirely if rain is forecast anywhere upstream — wadis flash-flood, and that is the one genuine danger here rather than a theoretical one.</p>
<h2>Living nearby</h2>
<p>The coast between Muscat and Sur is a string of small towns rather than a commuter belt, so this is weekend territory rather than somewhere most people live. <strong>Quriyat</strong> is the closest town of any size to the capital and has been drawing steady interest as the coast road has improved.</p>',
		),
		array(
			'title'   => 'Jebel Shams',
			'image'   => 'om-mountain',
			'region'  => 'Ad Dakhiliyah',
			'drive'   => 'About 2.5 hours',
			'best'    => 'October to April',
			'areas'   => 'Al Hamra, Nizwa, Bahla',
			'lat'     => '23.2381',
			'lng'     => '57.2622',
			'excerpt' => 'Oman\'s highest mountain, at roughly 3,000 metres, with a canyon below it that gets called the Grand Canyon of Arabia.',
			'content' => '<p>Jebel Shams — "mountain of the sun" — is the high point of the Al Hajar range and of Oman, at around 3,000 metres. The draw is not the summit, which is a restricted military area, but the rim: the plateau looks straight down into <strong>Wadi Ghul</strong>, a gorge deep enough to have earned the Grand Canyon comparison and to mostly deserve it.</p>
<p>The best-known walk is the <strong>Balcony Walk (W6)</strong>, which follows a ledge cut into the canyon wall from the abandoned village of As Sab to the deserted settlement of As Sab Bani Khamis. It is a few hours each way, largely level, and exposed enough that it is not for anyone uneasy with heights.</p>
<h2>Practicalities</h2>
<p>It is genuinely cold up there. Winter nights drop to around freezing and it occasionally snows, which is not what most people expect of Oman. The road is paved most of the way, with a rough final section better suited to four-wheel drive.</p>
<h2>Living nearby</h2>
<p><strong>Nizwa</strong>, the old interior capital, is the regional centre — a real town with a fort, a well-known Friday livestock market and a growing amount of new housing. <strong>Al Hamra</strong> and <strong>Bahla</strong> sit closer to the mountain. This is a different market from Muscat: lower prices, larger plots, and a much slower pace.</p>',
		),
		array(
			'title'   => 'Bandar Khayran',
			'image'   => 'om-khayran',
			'region'  => 'Muscat Governorate',
			'drive'   => 'About 40 minutes',
			'best'    => 'October to April',
			'areas'   => 'Qantab, Bandar Jissah, Yenkit',
			'lat'     => '23.5167',
			'lng'     => '58.7167',
			'excerpt' => 'A maze of limestone inlets and turquoise coves southeast of the capital, and the closest good snorkelling to the city.',
			'content' => '<p>Southeast of Muscat the coastline breaks up into a run of narrow limestone inlets, sheltered bays and small islands. That is Bandar Khayran. It is a protected area, the water is unusually clear for somewhere this close to a capital city, and it is reached either by boat from Marina Bandar Al Rowdha or by kayak from the closer bays.</p>
<p>The reefs are the reason most people go — the snorkelling and diving here are the best within easy reach of Muscat — but the inlets themselves are the attraction. There are wrecks, sea caves and beaches that can only be reached from the water.</p>
<h2>Living nearby</h2>
<p>The stretch between Muscat and Bandar Khayran — <strong>Qantab</strong>, <strong>Bandar Jissah</strong>, <strong>Yenkit</strong> — is where the resort-adjacent property sits. It is scenic, quieter than the western suburbs and increasingly built up, and it is one of the areas where foreign buyers can own freehold within an integrated tourism development. Expect a longer drive into the city than from Al Khuwair or Ghubrah.</p>',
		),
	);
}

/**
 * Download one image into the Media Library, or return the existing copy.
 *
 * @param string $key Image key from aaa_demo_images().
 * @return int Attachment ID, or 0 on failure.
 */
function aaa_import_image( string $key ): int {
	$images = aaa_demo_images();
	if ( ! isset( $images[ $key ] ) ) {
		return 0;
	}
	$image = $images[ $key ];

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
			'meta_value'     => $key,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = download_url( $image['url'], 60 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}

	$id = media_handle_sideload(
		array(
			'name'     => 'aaa-' . $key . '.jpg',
			'tmp_name' => $tmp,
		),
		0,
		$image['alt']
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return 0;
	}

	$id = (int) $id;

	update_post_meta( $id, '_aaa_demo_key', $key );
	update_post_meta( $id, '_wp_attachment_image_alt', $image['alt'] );
	update_post_meta( $id, '_aaa_credit_author', $image['author'] ?? '' );
	update_post_meta( $id, '_aaa_credit_license', $image['license'] ?? '' );
	update_post_meta( $id, '_aaa_credit_source', $image['source'] ?? '' );

	// Put the credit in the caption too, so it is visible in the Media Library.
	wp_update_post(
		array(
			'ID'           => $id,
			'post_excerpt' => sprintf(
				'%s — %s',
				$image['author'] ?? '',
				$image['license'] ?? ''
			),
		)
	);

	return $id;
}

/**
 * Import the team photo shipped inside the theme.
 *
 * Unlike the other demo images, this one's bytes live in the theme rather than
 * at a fixed remote URL, so it can change between theme versions. The source
 * file's hash is stored alongside the attachment and the old copy is discarded
 * when it no longer matches; without that, a theme update could ship a new
 * photograph that no existing site would ever pick up.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function aaa_import_team_photo(): int {
	$source = AAA_DIR . '/assets/img/team-photo.png';
	if ( ! is_readable( $source ) ) {
		return 0;
	}

	$hash = (string) md5_file( $source );

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
			'meta_value'     => 'team-photo',
		)
	);

	if ( $existing ) {
		$id = (int) $existing[0];
		if ( get_post_meta( $id, '_aaa_demo_hash', true ) === $hash ) {
			return $id;
		}
		// Superseded. Only ever an attachment this importer created itself - a
		// photo the office uploaded carries no _aaa_demo_key and is not found
		// by the query above.
		wp_delete_attachment( $id, true );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'aaa-team-photo.png' );
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return 0;
	}

	$id = media_handle_sideload(
		array(
			'name'     => 'aaa-team-photo.png',
			'tmp_name' => $tmp,
		),
		0,
		__( 'The Amir Al Afia Real Estate team', 'amir-al-afia' )
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return 0;
	}

	$id = (int) $id;

	update_post_meta( $id, '_aaa_demo_key', 'team-photo' );
	update_post_meta( $id, '_aaa_demo_hash', $hash );
	update_post_meta( $id, '_wp_attachment_image_alt', __( 'Two Amir Al Afia consultants in suits with the company lapel pin', 'amir-al-afia' ) );

	// Said plainly in the Media Library, the same way the location photographs
	// carry their credits: this is a stand-in, not the office.
	wp_update_post(
		array(
			'ID'           => $id,
			'post_excerpt' => __( 'Placeholder — replace with a photograph of the real team.', 'amir-al-afia' ),
		)
	);

	return $id;
}

/**
 * Create the demo content.
 *
 * Safe to re-run: media already imported is reused, and existing demo posts
 * have their photo refreshed rather than being duplicated — which is what
 * makes it possible to correct a wrong image by running this again.
 *
 * @return array<string, int> Counts, for the admin notice.
 */
function aaa_run_demo_import(): array {
	$result = array(
		'images'      => 0,
		'properties'  => 0,
		'agents'      => 0,
		'attractions' => 0,
		'pruned'      => 0,
		'regenerated' => 0,
	);

	// --- Media ---------------------------------------------------------
	$ids = array();
	foreach ( array_keys( aaa_demo_images() ) as $key ) {
		$id = aaa_import_image( $key );
		if ( $id ) {
			$ids[ $key ] = $id;
			++$result['images'];
		}
	}

	$team_photo = aaa_import_team_photo();
	if ( $team_photo ) {
		set_theme_mod( 'aaa_team_photo', $team_photo );
	}

	// --- Hero collage --------------------------------------------------
	// Eighteen slots, read six at a time as the left, middle and right columns.
	// Every key appears once: a repeat inside a column would defeat the point
	// of six-per-column, and a repeat across columns is visible side by side.
	$collage = array(
		// Left.
		'om-khuwair', 'om-almouj-night', 'om-house',
		'om-corniche', 'om-development', 'om-mosque',
		// Middle.
		'om-maritime', 'om-coast-res', 'om-almouj-boats',
		'om-mountain', 'om-office', 'om-wadi',
		// Right.
		'om-aerial', 'om-marina-air', 'om-almouj-front',
		'om-khayran', 'om-houses', 'om-desert',
	);
	foreach ( $collage as $i => $key ) {
		if ( isset( $ids[ $key ] ) ) {
			set_theme_mod( 'aaa_collage_' . ( $i + 1 ), $ids[ $key ] );
		}
	}

	// --- Properties ----------------------------------------------------
	foreach ( aaa_demo_properties() as $index => $item ) {
		$existing = get_page_by_path( sanitize_title( $item['title'] ), OBJECT, 'property' );

		if ( $existing ) {
			if ( isset( $ids[ $item['image'] ] ) ) {
				set_post_thumbnail( $existing->ID, $ids[ $item['image'] ] );
			}
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'property',
				'post_status'  => 'publish',
				'post_title'   => $item['title'],
				'post_excerpt' => $item['excerpt'],
				'post_content' => $item['excerpt'],
				'menu_order'   => $index,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_aaa_price', (string) $item['price'] );
		update_post_meta( $post_id, '_aaa_beds', (string) $item['beds'] );
		update_post_meta( $post_id, '_aaa_baths', (string) $item['baths'] );
		update_post_meta( $post_id, '_aaa_area', (string) $item['area'] );
		update_post_meta( $post_id, '_aaa_address', $item['address'] );

		// The home teaser shows two rows of four.
		if ( $index < 8 ) {
			update_post_meta( $post_id, '_aaa_featured', '1' );
		}

		wp_set_object_terms( $post_id, $item['type'], 'property_type' );
		wp_set_object_terms( $post_id, $item['deal'], 'deal_type' );

		if ( isset( $ids[ $item['image'] ] ) ) {
			set_post_thumbnail( $post_id, $ids[ $item['image'] ] );
		}

		++$result['properties'];
	}

	// --- Agents --------------------------------------------------------
	$agents = array(
		array(
			'name'     => 'Sara Al Harthi',
			'role'     => 'Seller',
			'title'    => 'Sales Consultant',
			'phone'    => '+968 9800 2323',
			'telegram' => 'amiralafia',
		),
		array(
			'name'     => 'Khalid Al Mandhari',
			'role'     => 'Support',
			'title'    => 'Investment Advisor',
			'phone'    => '+968 9800 2121',
			'telegram' => 'amiralafia',
		),
	);

	foreach ( $agents as $index => $agent ) {
		if ( get_page_by_path( sanitize_title( $agent['name'] ), OBJECT, 'agent' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'agent',
				'post_status' => 'publish',
				'post_title'  => $agent['name'],
				'menu_order'  => $index,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_aaa_agent_role', $agent['role'] );
		update_post_meta( $post_id, '_aaa_agent_title', $agent['title'] );
		update_post_meta( $post_id, '_aaa_agent_phone', $agent['phone'] );
		update_post_meta( $post_id, '_aaa_agent_telegram', $agent['telegram'] );

		++$result['agents'];
	}

	// --- Attractions ---------------------------------------------------
	foreach ( aaa_demo_attractions() as $index => $item ) {
		$existing = get_page_by_path( sanitize_title( $item['title'] ), OBJECT, 'attraction' );
		$post_id  = $existing ? (int) $existing->ID : 0;

		if ( ! $post_id ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'attraction',
					'post_status'  => 'publish',
					'post_title'   => $item['title'],
					'post_excerpt' => $item['excerpt'],
					'post_content' => $item['content'],
					'menu_order'   => $index,
				)
			);

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				continue;
			}

			++$result['attractions'];
		} else {
			// Refresh the body only while it is still ours to refresh.
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_excerpt' => $item['excerpt'],
					'post_content' => $item['content'],
					'menu_order'   => $index,
				)
			);
		}

		foreach ( array( 'region', 'drive', 'best', 'areas', 'lat', 'lng' ) as $field ) {
			if ( ! empty( $item[ $field ] ) ) {
				update_post_meta( $post_id, '_aaa_attr_' . $field, $item[ $field ] );
			}
		}

		if ( isset( $ids[ $item['image'] ] ) ) {
			set_post_thumbnail( $post_id, $ids[ $item['image'] ] );
		}
	}

	$result['pruned']      = aaa_prune_stale_demo_media();
	$result['regenerated'] = aaa_regenerate_demo_sizes( 'aaa-og' )
		+ aaa_regenerate_demo_sizes( 'aaa-hero-tile' );

	// The Oman guide is a new archive; make sure its permalinks resolve.
	flush_rewrite_rules();

	update_option( 'aaa_demo_imported', gmdate( 'c' ) );

	return $result;
}

/**
 * Regenerate intermediate sizes for demo attachments that are missing one.
 *
 * An image imported before a size was registered never gets that size — which
 * is exactly what happened to the first batch of place photos when the 1200x630
 * Open Graph size arrived a version later, leaving them without a share crop.
 * Core has no UI for this and the brief rules out a regeneration plugin, so the
 * importer repairs its own images.
 *
 * Scoped to attachments carrying a `_aaa_demo_key`; media the office uploaded
 * is never touched.
 *
 * @param string $size Size that must exist.
 * @return int How many were rebuilt.
 */
function aaa_regenerate_demo_sizes( string $size = 'aaa-og' ): int {
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
		)
	);

	$rebuilt = 0;

	foreach ( $attachments as $id ) {
		$id   = (int) $id;
		$meta = wp_get_attachment_metadata( $id );

		if ( isset( $meta['sizes'][ $size ] ) ) {
			continue;
		}

		// Too small to crop to this size at all; nothing to rebuild.
		$dimensions = wp_get_registered_image_subsizes()[ $size ] ?? null;
		if ( $dimensions && ( ( $meta['width'] ?? 0 ) < $dimensions['width'] || ( $meta['height'] ?? 0 ) < $dimensions['height'] ) ) {
			continue;
		}

		$file = get_attached_file( $id );
		if ( ! $file || ! file_exists( $file ) ) {
			continue;
		}

		$fresh = wp_generate_attachment_metadata( $id, $file );
		if ( is_array( $fresh ) ) {
			wp_update_attachment_metadata( $id, $fresh );
			++$rebuilt;
		}
	}

	return $rebuilt;
}

/**
 * Delete demo attachments this theme imported under a key it no longer uses.
 *
 * Scoped deliberately narrowly: it only ever touches attachments carrying a
 * `_aaa_demo_key` this importer wrote, and only when that key has dropped out
 * of aaa_demo_images(). Media the office uploaded is never considered.
 *
 * @return int How many were removed.
 */
function aaa_prune_stale_demo_media(): int {
	$current   = array_keys( aaa_demo_images() );
	$current[] = 'team-photo';

	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
		)
	);

	$removed = 0;
	foreach ( $attachments as $id ) {
		$key = (string) get_post_meta( $id, '_aaa_demo_key', true );
		if ( in_array( $key, $current, true ) ) {
			continue;
		}
		if ( wp_delete_attachment( (int) $id, true ) ) {
			++$removed;
		}
	}

	return $removed;
}

/**
 * Add the importer under Appearance.
 */
function aaa_demo_menu(): void {
	add_theme_page(
		__( 'Starter Content', 'amir-al-afia' ),
		__( 'Starter Content', 'amir-al-afia' ),
		'edit_theme_options',
		'aaa-starter-content',
		'aaa_render_demo_page'
	);
}
add_action( 'admin_menu', 'aaa_demo_menu' );

/**
 * Render the Starter Content screen.
 */
function aaa_render_demo_page(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$done = null;

	if ( isset( $_POST['aaa_import_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['aaa_import_nonce'] ) ), 'aaa_import_demo' ) ) {
		set_time_limit( 600 );
		$done = aaa_run_demo_import();
	}

	$imported = get_option( 'aaa_demo_imported' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Starter Content', 'amir-al-afia' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: image count, 2: property count, 3: agent count, 4: attraction count, 5: removed count, 6: rebuilt count. */
						esc_html__( 'Imported %1$d images, %2$d properties, %3$d agents and %4$d attraction pages. Removed %5$d images no longer used, rebuilt sizes for %6$d.', 'amir-al-afia' ),
						(int) $done['images'],
						(int) $done['properties'],
						(int) $done['agents'],
						(int) $done['attractions'],
						(int) $done['pruned'],
						(int) $done['regenerated']
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Downloads the photography into your Media Library, then creates the demo properties, agents and Oman guide pages so the home page has content to show.', 'amir-al-afia' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Running it again is safe. Images already imported are reused, and demo pages have their photo and text refreshed rather than duplicated — so this is also how you pick up a corrected photo.', 'amir-al-afia' ); ?>
		</p>
		<p>
			<strong><?php esc_html_e( 'Note:', 'amir-al-afia' ); ?></strong>
			<?php esc_html_e( 'Every photo is a real photo of Oman, under a Creative Commons or public-domain licence, credited on the attachment. The building photos are placeholders for real listing photography — replace them before launch. The photos of places can stay.', 'amir-al-afia' ); ?>
		</p>

		<?php if ( $imported ) : ?>
			<p>
				<em>
					<?php
					printf(
						/* translators: %s: date the import last ran. */
						esc_html__( 'Last run: %s', 'amir-al-afia' ),
						esc_html( (string) $imported )
					);
					?>
				</em>
			</p>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'aaa_import_demo', 'aaa_import_nonce' ); ?>
			<p>
				<button type="submit" class="button button-primary">
					<?php esc_html_e( 'Import starter content', 'amir-al-afia' ); ?>
				</button>
			</p>
		</form>
	</div>
	<?php
}
