<!DOCTYPE html>
<html lang="en-US">
<head>
<title>RSS NEWS</title
<link rel="stylesheet" type="text/css" href="style.css?refresh=1">
</head>
	
<body>

<div id="form">
<form name="news-selection" method="POST" action="">
<select name="weights">
<option value="">Select preset...</option>
<option value="positive_strict">positive_strict</option>
<option value="aggressive_strict">aggressive_strict</option>
<option value="positive_weighed">positive_weighed</option>
<option value="negative_weighed">negative_weighed</option>
<option value="csv">CSV...</option>
</select> CSV wordlist (comma separated) <input type="text" name="csv" size="50" value="" /> Articles per site: <input type="number" name="num_value" size="2" value="10" min="5" max="50"/>
<input type="submit" value="update news">
</form>

</div>
	
<div id="rss">
		
	<?php
	
	$csv_filter = false;

	if(isset($_POST['weights'])) {
		$global_filter = $_POST['weights'];
		} elseif(isset($_GET['filter'])) {
		$global_filter = $_GET['filter'];
		} else {
		echo "<h3>No filter selected! defaulting to all.</h3>";
	}
	
	if(isset($_POST['csv'])) {
		$csv_filter = $_POST['csv'];
		$global_filter = 'csv';
		} else {
		$csv_filter = false;
	}
	
	if(isset($_REQUEST['num_value'])) {
		$maxarticles = (int)$_REQUEST['num_value'];
		} else {
		$maxarticles = 10;
	}
	
	$feeds = array(
	'http://www.dailymail.co.uk/articles.rss',
	'http://www.nytimes.com/services/xml/rss/nyt/Americas.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Arts.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/AsiaPacific.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/DiningandWine.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Europe.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/FashionandStyle.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Health.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Movies.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/NYRegion.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Obituaries.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Opinion.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Science.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Sports.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Television.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Theater.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/TheCity.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/World.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/WorldBusiness.xml',
	'http://en.rian.ru/export/rss2/index.xml',
	'http://feeds.abcnews.com/abcnews/politicsheadlines',
	'http://feeds.bbci.co.uk/news/business/rss.xml',
	'http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml',
	'http://feeds.bbci.co.uk/news/rss.xml',
	'http://feeds.bbci.co.uk/news/science_and_environment/rss.xml',
	'http://feeds.bbci.co.uk/news/technology/rss.xml',
	'http://feeds.bbci.co.uk/news/world/rss.xml',
	'http://feeds.bbci.co.uk/news/world/rss.xml',
	'http://feeds.feedburner.com/avc',
	'http://feeds.feedburner.com/fastcompany/headlines',
	'http://feeds.feedburner.com/JamesFallows',
	'http://feeds.feedburner.com/JonathanChaitRssFeed',
	'http://feeds.feedburner.com/pbs/mediashift-blog',
	'http://feeds.feedburner.com/PoliticalWire',
	'http://feeds.feedburner.com/realclearpolitics/qlMj',
	'http://feeds.feedburner.com/scotusblog/pFXs',
	'http://feeds.feedburner.com/talking-points-memo',
	'http://feeds.feedburner.com/thedailybeast/articles',
	'http://feeds.feedburner.com/TheWirecutter',
	'http://feeds.feedburner.com/Torrentfreak',
	'http://feeds.foxnews.com/foxnews/latest',
	'http://feeds.foxnews.com/foxnews/politics?format=xml',
	'http://feeds.latimes.com/movies/reviews/',
	'http://feeds.nytimes.com/nyt/rss/Books',
	'http://feeds.publicradio.org/public_feeds/apm-reports/rss/rss',
	'http://feeds.reuters.com/reuters/INbusinessNews',
	'http://feeds.reuters.com/reuters/worldNews',
	'http://feeds.washingtonpost.com/rss/entertainment',
	'http://feeds.washingtonpost.com/rss/lifestyle',
	'http://feeds.washingtonpost.com/rss/local',
	'http://feeds.washingtonpost.com/rss/national',
	'http://feeds.washingtonpost.com/rss/opinions',
	'http://feeds.washingtonpost.com/rss/politics',
	'http://feeds.washingtonpost.com/rss/rss_capital-weather-gang',
	'http://feeds.washingtonpost.com/rss/rss_early-lead',
	'http://feeds.washingtonpost.com/rss/rss_right-turn',
	'http://feeds.washingtonpost.com/rss/rss_the-fix',
	'http://feeds.washingtonpost.com/rss/sports/blogs-columns',
	'http://feeds.washingtonpost.com/rss/world',
	'http://georgelakoff.com/feed/',
	'http://hnrss.org/newest?points=200',
	'http://krugman.blogs.nytimes.com/feed/',
	'http://mediagazer.com/feed.xml',
	'http://mondaynote.com/feed',
	'http://motherboard.vice.com/en_us/rss',
	'http://om.co/feed/',
	'http://pressthink.org/feed/',
	'http://qz.com/feed/',
	'http://qz.com/feed/',
	'http://radio3.io/users/davewiner/rss.xml',
	'http://rss.cbc.ca/lineup/world.xml',
	'http://rss.dw.de/rdf/rss-en-world',
	'http://rss.nytimes.com/services/xml/rss/nyt/Politics.xml',
	'http://rss.nytimes.com/services/xml/rss/nyt/sunday-review.xml',
	'http://rssfeeds.usatoday.com/UsatodaycomMovies-TopStories',
	'http://stratechery.com/feed/',
	'http://talkingpointsmemo.com/feed/all',
	'http://thinkprogress.org/feed',
	'http://truth-out.org/feed?format=feed',
	'http://www.aclu.org/taxonomy/feed-term/362/feed',
	'http://www.aljazeera.com/Services/Rss/?PostingId=2007731105943979989',
	'http://www.axios.com/feeds/feed.rss',
	'http://www.ben-evans.com/benedictevans?format=RSS',
	'http://www.businessoffashion.com/syndication/feed',
	'http://www.buzzfeed.com/tech.xml',
	'http://www.cbc.ca/cmlink/rss-canada',
	'http://www.cbc.ca/cmlink/rss-technology',
	'http://www.cbc.ca/cmlink/rss-topstories',
	'http://www.cbc.ca/cmlink/rss-world',
	'http://www.cbsnews.com/latest/rss/face-the-nation',
	'http://www.cbsnews.com/latest/rss/politics',
	'http://www.dailywire.com/rss.xml',
	'http://www.economist.com/sections/business-finance/rss.xml',
	'http://www.engadget.com/rss.xml',
	'http://www.europeanvoice.com/feed/',
	'http://www.internethistorypodcast.com/feed/',
	'http://www.memeorandum.com/feed.xml',
	'http://www.niemanlab.org/feed/',
	'http://www.npr.org/rss/rss.php?id=1014',
	'http://www.nytimes.com/services/xml/rss/nyt/Americas.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Arts.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/AsiaPacific.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/DiningandWine.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Europe.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/FashionandStyle.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Health.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Movies.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/NYRegion.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Obituaries.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Opinion.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Science.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Sports.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Television.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/Theater.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/TheCity.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/World.xml',
	'http://www.nytimes.com/services/xml/rss/nyt/WorldBusiness.xml',
	'http://www.nytimes.com/svc/collections/v1/publish/http://www.nytimes.com/by/dan-barry/rss.xml',
	'http://www.politico.com/rss/politicopicks.xml',
	'http://www.poynter.org/feed/',
	'http://www.slate.com/articles/news_and_politics.fulltext.all.rss',
	'http://www.spiegel.de/international/business/index.rss',
	'http://www.spiegel.de/international/europe/index.rss',
	'http://www.spiegel.de/international/index.rss',
	'http://www.techmeme.com/index.xml',
	'http://www.theatlantic.com/feed/all/',
	'http://www.theguardian.com/books/rss',
	'http://www.theguardian.com/business/diversity-and-equality/rss',
	'http://www.theguardian.com/business/economics/rss',
	'http://www.theguardian.com/lifeandstyle/women/rss',
	'http://www.theguardian.com/music/rss',
	'http://www.theguardian.com/stage/rss',
	'http://www.theguardian.com/tv-and-radio/rss',
	'http://www.theguardian.com/uk-news/rss',
	'http://www.theguardian.com/us/film/rss',
	'http://www.theguardian.com/world/americas/rss',
	'http://www.theguardian.com/world/asia/rss',
	'http://www.theguardian.com/world/europe-news/rss',
	'http://www.theguardian.com/world/middleeast/rss',
	'http://www.variety.com/rss.asp?categoryid=10',
	'http://www.vox.com/rss/index.xml',
	'http://www.wired.com/feed/',
	'http://www.wsj.com/xml/rss/3_7041.xml');


	function filter($string,$global_filter,$csv_filter) {

		$filter = $global_filter;

		if($filter) {
			
			$words_positive = array('laughter','happiness','happy','laughed','laugh','laughing','excellent','laughs',
						'joy','love','successful','win','rainbow','smile','won','pleasure','smiled',
						'rainbows','winning','celebration','enjoyed','healthy','music','celebrating',
						'congratulations','weekend','celebrate','comedy','jokes','rich','victory',
						'christmas','free','friendship','fun','holidays','loved','loves','loving',
						'beach','hahaha','kissing','sunshine','beautiful','delicious','friends',
						'funny','outstanding','paradise','sweetest','vacation','butterflies',
						'freedom','flower','great','sunlight','sweetheart','sweetness','award',
						'chocolate','hahahaha','heaven','peace','splendid','success','enjoying',
						'kissed','attraction','celebrated','hero','hugs','positive','sun','birthday',
						'blessed','fantastic','winner','delight','beauty','butterfly','entertainment',
						'funniest','honesty','sky','smiles','succeed','wonderful','glorious','kisses',
						'promotion','family','gift','humor','romantic','cupcakes','festival','haha',
						'honour','relax','weekends');
			
			$words_aggressive = array('terror','terrorist','dead','deaths','war','knife','guns','violent','attacks',
						  'attacked','killing','killed','murder','murders','rape','rapist','gunman','gunfire',
						  'bullets','attack','murder','murderer','murderous','murderously');
			
			$words_negative = array('abnormal','abolish','abominable','abominably','abominate','abomination','abort',
						'aborted','aborts','abrade','abrasive','abrupt','abruptly','abscond','absence',
						'absent-minded','absentee','absurd','absurdity','absurdly','absurdness','abuse',
						'abused','abuses','abusive','abysmal','abysmally','abyss','accidental','accost',
						'accursed','accusation','accusations','accuse','accuses','accusing','accusingly',
						'acerbate','acerbic','acerbically','ache','ached','aches','achey','aching','acrid',
						'acridly','acridness','acrimonious','acrimoniously','acrimony','adamant','adamantly',
						'addict','addicted','addicting','addicts','admonish','admonisher','admonishingly',
						'admonishment','admonition','adulterate','adulterated','adulteration','adulterier',
						'adversarial','adversary','adverse','adversity','afflict','affliction','afflictive',
						'affront','afraid','aggravate','aggravating','aggravation','aggression','aggressive',
						'aggressiveness','aggressor','aggrieve','aggrieved','aggrivation','aghast','agonies',
						'agonize','agonizing','agonizingly','agony','aground','ail','ailing','ailment',
						'aimless','alarm','alarmed','alarming','alarmingly','alienate','alienated',
						'alienation','allegation','allegations','allege','allergic','allergies','allergy',
						'aloof','altercation','ambiguity','ambiguous','ambivalence','ambivalent','ambush',
						'amiss','amputate','anarchism','anarchist','anarchistic','anarchy','anemic','anger',
						'angrily','angriness','angry','anguish','animosity','annihilate','annihilation',
						'annoy','annoyance','annoyances','annoyed','annoying','annoyingly','annoys',
						'anomalous','anomaly','antagonism','antagonist','antagonistic','antagonize',
						'anti-','anti-american','anti-israeli','anti-occupation','anti-proliferation',
						'anti-semites','anti-social','anti-us','anti-black','anti-white','antipathy','antiquated',
						'antithetical','anxieties','anxiety','anxious','anxiously','anxiousness',
						'apathetic','apathetically','apathy','apocalypse','apocalyptic','apologist',
						'apologists','appal','appall','appalled','appalling','appallingly','apprehension',
						'apprehensions','apprehensive','apprehensively','arbitrary','arcane','archaic',
						'arduous','arduously','argumentative','arrogance','arrogant','arrogantly',
						'ashamed','asinine','asininely','asinininity','askance','asperse','aspersion',
						'aspersions','assail','assassin','assassinate','assault','assult','astray',
						'asunder','atrocious','atrocities','atrocity','atrophy','attack','attacks',
						'audacious','audaciously','audaciousness','audacity','audiciously','austere',
						'authoritarian','autocrat','autocratic','avalanche','avarice','avaricious',
						'avariciously','avenge','averse','aversion','aweful','awful','awfully','awfulness',
						'awkward','awkwardness','ax','babble','back-logged','back-wood','back-woods',
						'backache','backaches','backaching','backbite','backbiting','backward','backwardness',
						'backwood','backwoods','bad','badly','baffle','baffled','bafflement','baffling',
						'bait','balk','banal','banalize','bane','banish','banishment','bankrupt',
						'barbarian','barbaric','barbarically','barbarity','barbarous','barbarously',
						'barren','baseless','bash','bashed','bashful','bashing','bastard','bastards',
						'battered','battering','batty','bearish','beastly','bedlam','bedlamite','befoul',
						'beg','beggar','beggarly','begging','beguile','belabor','belated','beleaguer','belie',
						'belittle','belittled','belittling','bellicose','belligerence','belligerent',
						'belligerently','bemoan','bemoaning','bemused','bent','berate','bereave','bereavement',
						'bereft','berserk','beseech','beset','besiege','besmirch','bestial','betray',
						'betrayal','betrayals','betrayer','betraying','betrays','bewail','beware','bewilder',
						'bewildered','bewildering','bewilderingly','bewilderment','bewitch','bias','biased',
						'biases','bicker','bickering','bid-rigging','bigotries','bigotry','bitch','bitchy',
						'biting','bitingly','bitter','bitterly','bitterness','bizarre','blab','blabber',
						'blackmail','blah','blame','blameworthy','bland','blandish','blaspheme','blasphemous',
						'blasphemy','blasted','blatant','blatantly','blather','bleak','bleakly','bleakness',
						'bleed','bleeding','bleeds','blemish','blind','blinding','blindingly','blindside',
						'blister','blistering','bloated','blockage','blockhead','bloodshed','bloodthirsty',
						'bloody','blotchy','blow','blunder','blundering','blunders','blunt','blur','bluring',
						'blurred','blurring','blurry','blurs','blurt','boastful','boggle','bogus','boil',
						'boiling','boisterous','bomb','bombard','bombardment','bombastic','bondage','bonkers',
						'bore','bored','boredom','bores','boring','botch','bother','bothered','bothering',
						'bothers','bothersome','bowdlerize','boycott','braggart','bragger','brainless',
						'brainwash','brash','brashly','brashness','brat','bravado','brazen','brazenly',
						'brazenness','breach','break','break-up','break-ups','breakdown','breaking','breaks',
						'breakup','breakups','bribery','brimstone','bristle','brittle','broke','broken',
						'broken-hearted','brood','browbeat','bruise','bruised','bruises','bruising','brusque',
						'brutal','brutalising','brutalities','brutality','brutalize','brutalizing','brutally',
						'brute','brutish','bs','buckle','bug','bugging','buggy','bugs','bulkier','bulkiness',
						'bulky','bulkyness','bull****','bull----','bullies','bullshit','bullshyt','bully',
						'bullying','bullyingly','bum','bump','bumped','bumping','bumpping','bumps','bumpy',
						'bungle','bungler','bungling','bunk','burden','burdensome','burdensomely','burn',
						'burned','burning','burns','bust','busts','busybody','butcher','butchery','buzzing',
						'byzantine','cackle','calamities','calamitous','calamitously','calamity','callous',
						'calumniate','calumniation','calumnies','calumnious','calumniously','calumny',
						'cancer','cancerous','cannibal','cannibalize','capitulate','capricious','capriciously',
						'capriciousness','capsize','careless','carelessness','caricature','carnage','carp',
						'cartoonish','cash-strapped','castigate','castrated','casualty','cataclysm',
						'cataclysmal','cataclysmic','cataclysmically','catastrophe','catastrophes',
						'catastrophic','catastrophically','catastrophies','caustic','caustically',
						'cautionary','cave','censure','chafe','chaff','chagrin','challenging','chaos',
						'chaotic','chasten','chastise','chastisement','chatter','chatterbox','cheap',
						'cheapen','cheaply','cheat','cheated','cheater','cheating','cheats','checkered',
						'cheerless','cheesy','chide','childish','chill','chilly','chintzy','choke',
						'choleric','choppy','chore','chronic','chunky','clamor','clamorous','clash',
						'cliche','cliched','clique','clog','clogged','clogs','cloud','clouding','cloudy',
						'clueless','clumsy','clunky','coarse','cocky','coerce','coercion','coercive','cold',
						'coldly','collapse','collude','collusion','combative','combust','comical','commiserate',
						'commonplace','commotion','commotions','complacent','complain','complained',
						'complaining','complains','complaint','complaints','complex','complicated',
						'complication','complicit','compulsion','compulsive','concede','conceded',
						'conceit','conceited','concen','concens','concern','concerned','concerns',
						'concession','concessions','condemn','condemnable','condemnation','condemned',
						'condemns','condescend','condescending','condescendingly','condescension','confess',
						'confession','confessions','confined','conflict','conflicted','conflicting','conflicts',
						'confound','confounded','confounding','confront','confrontation','confrontational',
						'confuse','confused','confuses','confusing','confusion','confusions','congested',
						'congestion','cons','conscons','conservative','conspicuous','conspicuously',
						'conspiracies','conspiracy','conspirator','conspiratorial','conspire','consternation',
						'contagious','contaminate','contaminated','contaminates','contaminating',
						'contamination','contempt','contemptible','contemptuous','contemptuously','contend',
						'contention','contentious','contort','contortions','contradict','contradiction',
						'contradictory','contrariness','contravene','contrive','contrived','controversial',
						'controversy','convoluted','corrode','corrosion','corrosions','corrosive','corrupt',
						'corrupted','corrupting','corruption','corrupts','corruptted','costlier','costly',
						'counter-productive','counterproductive','coupists','covetous','coward','cowardly',
						'crabby','crack','cracked','cracks','craftily','craftly','crafty','cramp','cramped',
						'cramping','cranky','crap','crappy','craps','crash','crashed','crashes','crashing',
						'crass','craven','cravenly','craze','crazily','craziness','crazy','creak','creaking',
						'creaks','credulous','creep','creeping','creeps','creepy','crept','crime','criminal',
						'cringe','cringed','cringes','cripple','crippled','cripples','crippling','crisis',
						'critic','critical','criticism','criticisms','criticize','criticized','criticizing',
						'critics','cronyism','crook','crooked','crooks','crowded','crowdedness','crude',
						'cruel','crueler','cruelest','cruelly','cruelness','cruelties','cruelty','crumble',
						'crumbling','crummy','crumple','crumpled','crumples','crush','crushed','crushing',
						'cry','culpable','culprit','cumbersome','cunt','cunts','cuplrit','curse','cursed',
						'curses','curt','cuss','cussed','cutthroat','cynical','cynicism','d*mn','damage',
						'damaged','damages','damaging','damn','damnable','damnably','damnation','damned',
						'damning','damper','danger','dangerous','dangerousness','dark','darken','darkened',
						'darker','darkness','dastard','dastardly','daunt','daunting','dauntingly','dawdle',
						'daze','dazed','dead','deadbeat','deadlock','deadly','deadweight','deaf','dearth',
						'death','debacle','debase','debasement','debaser','debatable','debauch','debaucher',
						'debauchery','debilitate','debilitating','debility','debt','debts','decadence',
						'decadent','decay','decayed','deceit','deceitful','deceitfully','deceitfulness',
						'deceive','deceiver','deceivers','deceiving','deception','deceptive','deceptively',
						'declaim','decline','declines','declining','decrement','decrepit','decrepitude',
						'decry','defamation','defamations','defamatory','defame','defect','defective',
						'defects','defensive','defiance','defiant','defiantly','deficiencies','deficiency',
						'deficient','defile','defiler','deform','deformed','defrauding','defunct','defy',
						'degenerate','degenerately','degeneration','degradation','degrade','degrading',
						'degradingly','dehumanization','dehumanize','deign','deject','dejected','dejectedly',
						'dejection','delay','delayed','delaying','delays','delinquency','delinquent',
						'delirious','delirium','delude','deluded','deluge','delusion','delusional','delusions',
						'demean','demeaning','demise','demolish','demolisher','demon','demonic','demonize',
						'demonized','demonizes','demonizing','demoralize','demoralizing','demoralizingly',
						'denial','denied','denies','denigrate','denounce','dense','dent','dented','dents',
						'denunciate','denunciation','denunciations','deny','denying','deplete','deplorable',
						'deplorably','deplore','deploring','deploringly','deprave','depraved','depravedly',
						'deprecate','depress','depressed','depressing','depressingly','depression','depressions',
						'deprive','deprived','deride','derision','derisive','derisively','derisiveness',
						'derogatory','desecrate','desert','desertion','desiccate','desiccated','desititute',
						'desolate','desolately','desolation','despair','despairing','despairingly','desperate',
						'desperately','desperation','despicable','despicably','despise','despised','despoil',
						'despoiler','despondence','despondency','despondent','despondently','despot','despotic',
						'despotism','destabilisation','destains','destitute','destitution','destroy','destroyer',
						'destruction','destructive','desultory','deter','deteriorate','deteriorating',
						'deterioration','deterrent','detest','detestable','detestably','detested','detesting',
						'detests','detract','detracted','detracting','detraction','detracts','detriment',
						'detrimental','devastate','devastated','devastates','devastating','devastatingly',
						'devastation','deviate','deviation','devil','devilish','devilishly','devilment',
						'devilry','devious','deviously','deviousness','devoid','diabolic','diabolical',
						'diabolically','diametrically','diappointed','diatribe','diatribes','dick','dictator',
						'dictatorial','die','die-hard','died','dies','difficult','difficulties','difficulty',
						'diffidence','dilapidated','dilemma','dilly-dally','dim','dimmer','din','ding','dings',
						'dinky','dire','direly','direness','dirt','dirtbag','dirtbags','dirts','dirty',
						'disable','disabled','disaccord','disadvantage','disadvantaged','disadvantageous',
						'disadvantages','disaffect','disaffected','disaffirm','disagree','disagreeable',
						'disagreeably','disagreed','disagreeing','disagreement','disagrees','disallow',
						'disapointed','disapointing','disapointment','disappoint','disappointed','disappointing',
						'disappointingly','disappointment','disappointments','disappoints','disapprobation',
						'disapproval','disapprove','disapproving','disarm','disarray','disaster','disasterous',
						'disastrous','disastrously','disavow','disavowal','disbelief','disbelieve','disbeliever',
						'disclaim','discombobulate','discomfit','discomfititure','discomfort','discompose',
						'disconcert','disconcerted','disconcerting','disconcertingly','disconsolate',
						'disconsolately','disconsolation','discontent','discontented','discontentedly',
						'discontinued','discontinuity','discontinuous','discord','discordance','discordant',
						'discountenance','discourage','discouragement','discouraging','discouragingly',
						'discourteous','discourteously','discoutinous','discredit','discrepant','discriminate',
						'discrimination','discriminatory','disdain','disdained','disdainful','disdainfully',
						'disfavor','disgrace','disgraced','disgraceful','disgracefully','disgruntle',
						'disgruntled','disgust','disgusted','disgustedly','disgustful','disgustfully',
						'disgusting','disgustingly','dishearten','disheartening','dishearteningly','dishonest',
						'dishonestly','dishonesty','dishonor','dishonorable','dishonorablely','disillusion',
						'disillusioned','disillusionment','disillusions','disinclination','disinclined',
						'disingenuous','disingenuously','disintegrate','disintegrated','disintegrates',
						'disintegration','disinterest','disinterested','dislike','disliked','dislikes',
						'disliking','dislocated','disloyal','disloyalty','dismal','dismally','dismalness',
						'dismay','dismayed','dismaying','dismayingly','dismissive','dismissively',
						'disobedience','disobedient','disobey','disoobedient','disorder','disordered',
						'disorderly','disorganized','disorient','disoriented','disown','disparage',
						'disparaging','disparagingly','dispensable','dispirit','dispirited','dispiritedly',
						'dispiriting','displace','displaced','displease','displeased','displeasing',
						'displeasure','disproportionate','disprove','disputable','dispute','disputed',
						'disquiet','disquieting','disquietingly','disquietude','disregard','disregardful',
						'disreputable','disrepute','disrespect','disrespectable','disrespectablity',
						'disrespectful','disrespectfully','disrespectfulness','disrespecting','disrupt',
						'disruption','disruptive','diss','dissapointed','dissappointed','dissappointing',
						'dissatisfaction','dissatisfactory','dissatisfied','dissatisfies','dissatisfy',
						'dissatisfying','dissed','dissemble','dissembler','dissension','dissent','dissenter',
						'dissention','disservice','disses','dissidence','dissident','dissidents','dissing',
						'dissocial','dissolute','dissolution','dissonance','dissonant','dissonantly',
						'dissuade','dissuasive','distains','distaste','distasteful','distastefully','distort',
						'distorted','distortion','distorts','distract','distracting','distraction',
						'distraught','distraughtly','distraughtness','distress','distressed','distressing',
						'distressingly','distrust','distrustful','distrusting','disturb','disturbance',
						'disturbed','disturbing','disturbingly','disunity','disvalue','divergent','divisive',
						'divisively','divisiveness','dizzing','dizzingly','dizzy','doddering','dodgey',
						'dogged','doggedly','dogmatic','doldrums','domineer','domineering','donside','doom',
						'doomed','doomsday','dope','doubt','doubtful','doubtfully','doubts','douchbag',
						'douchebag','douchebags','downbeat','downcast','downer','downfall','downfallen',
						'downgrade','downhearted','downheartedly','downhill','downside','downsides',
						'downturn','downturns','drab','draconian','draconic','drag','dragged','dragging',
						'dragoon','drags','drain','drained','draining','drains','drastic','drastically',
						'drawback','drawbacks','dread','dreadful','dreadfully','dreadfulness','dreary',
						'dripped','dripping','drippy','drips','drones','droop','droops','drop-out','drop-outs',
						'dropout','dropouts','drought','drowning','drunk','drunkard','drunken','dubious',
						'dubiously','dubitable','dud','dull','dullard','dumb','dumbfound','dump','dumped',
						'dumping','dumps','dunce','dungeon','dungeons','dupe','dust','dusty','dwindling',
						'dying','earsplitting','eccentric','eccentricity','effigy','effrontery','egocentric',
						'egomania','egotism','egotistical','egotistically','egregious','egregiously',
						'election-rigger','elimination','emaciated','emasculate','embarrass','embarrassing',
						'embarrassingly','embarrassment','embattled','embroil','embroiled','embroilment',
						'emergency','emphatic','emphatically','emptiness','encroach','encroachment','endanger',
						'enemies','enemy','enervate','enfeeble','enflame','engulf','enjoin','enmity','enrage',
						'enraged','enraging','enslave','entangle','entanglement','entrap','entrapment',
						'envious','enviously','enviousness','epidemic','equivocal','erase','erode','erodes',
						'erosion','err','errant','erratic','erratically','erroneous','erroneously','error',
						'errors','eruptions','escapade','eschew','estranged','evade','evasion','evasive',
						'evil','evildoer','evils','eviscerate','exacerbate','exagerate','exagerated',
						'exagerates','exaggerate','exaggeration','exasperate','exasperated','exasperating',
						'exasperatingly','exasperation','excessive','excessively','exclusion','excoriate',
						'excruciating','excruciatingly','excuse','excuses','execrate','exhaust','exhausted',
						'exhaustion','exhausts','exhorbitant','exhort','exile','exorbitant','exorbitantance',
						'exorbitantly','expel','expensive','expire','expired','explode','exploit','exploitation',
						'explosive','expropriate','expropriation','expulse','expunge','exterminate',
						'extermination','extinguish','extort','extortion','extraneous','extravagance',
						'extravagant','extravagantly','extremism','extremist','extremists','eyesore','f**k',
						'fabricate','fabrication','facetious','facetiously','fail','failed','failing','fails',
						'failure','failures','faint','fainthearted','faithless','fake','fall','fallacies',
						'fallacious','fallaciously','fallaciousness','fallacy','fallen','falling','fallout',
						'falls','false','falsehood','falsely','falsify','falter','faltered','famine',
						'famished','fanatic','fanatical','fanatically','fanaticism','fanatics','fanciful',
						'far-fetched','farce','farcical','farcical-yet-provocative','farcically','farfetched',
						'fascism','fascist','fastidious','fastidiously','fastuous','fat','fat-cat','fat-cats',
						'fatal','fatalistic','fatalistically','fatally','fatcat','fatcats','fateful','fatefully',
						'fathomless','fatigue','fatigued','fatique','fatty','fatuity','fatuous','fatuously',
						'fault','faults','faulty','fawningly','faze','fear','fearful','fearfully','fears',
						'fearsome','feckless','feeble','feeblely','feebleminded','feign','feint','fell',
						'felon','felonious','ferociously','ferocity','fetid','fever','feverish','fevers',
						'fiasco','fib','fibber','fickle','fiction','fictional','fictitious','fidget','fidgety',
						'fiend','fiendish','fierce','figurehead','filth','filthy','finagle','finicky','fissures',
						'fist','flabbergast','flabbergasted','flagging','flagrant','flagrantly','flair','flairs',
						'flak','flake','flakey','flakieness','flaking','flaky','flare','flares','flareup',
						'flareups','flat-out','flaunt','flaw','flawed','flaws','flee','fleed','fleeing',
						'fleer','flees','fleeting','flicering','flicker','flickering','flickers','flighty',
						'flimflam','flimsy','flirt','flirty','floored','flounder','floundering','flout',
						'fluster','foe','fool','fooled','foolhardy','foolish','foolishly','foolishness',
						'forbid','forbidden','forbidding','forceful','foreboding','forebodingly','forfeit',
						'forged','forgetful','forgetfully','forgetfulness','forlorn','forlornly','forsake',
						'forsaken','forswear','foul','foully','foulness','fractious','fractiously','fracture',
						'fragile','fragmented','frail','frantic','frantically','franticly','fraud','fraudulent',
						'fraught','frazzle','frazzled','freak','freaking','freakish','freakishly','freaks',
						'freeze','freezes','freezing','frenetic','frenetically','frenzied','frenzy','fret',
						'fretful','frets','friction','frictions','fried','friggin','frigging','fright',
						'frighten','frightening','frighteningly','frightful','frightfully','frigid','frost',
						'frown','froze','frozen','fruitless','fruitlessly','frustrate','frustrated',
						'frustrates','frustrating','frustratingly','frustration','frustrations','fuck',
						'fucking','fudge','fugitive','full-blown','fulminate','fumble','fume','fumes',
						'fundamentalism','funky','funnily','funny','furious','furiously','furor','fury',
						'fuss','fussy','fustigate','fusty','futile','futilely','futility','fuzzy','gabble',
						'gaff','gaffe','gainsay','gainsayer','gall','galling','gallingly','galls','gangster',
						'gape','garbage','garish','gasp','gauche','gaudy','gawk','gawky','geezer','genocide',
						'get-rich','ghastly','ghetto','ghosting','gibber','gibberish','gibe','giddy','gimmick',
						'gimmicked','gimmicking','gimmicks','gimmicky','glare','glaringly','glib','glibly',
						'glitch','glitches','gloatingly','gloom','gloomy','glower','glum','glut','gnawing',
						'goad','goading','god-awful','goof','goofy','goon','gossip','graceless','gracelessly',
						'graft','grainy','grapple','grate','grating','gravely','greasy','greed','greedy',
						'grief','grievance','grievances','grieve','grieving','grievous','grievously','grim',
						'grimace','grind','gripe','gripes','grisly','gritty','gross','grossly','grotesque',
						'grouch','grouchy','groundless','grouse','growl','grudge','grudges','grudging',
						'grudgingly','gruesome','gruesomely','gruff','grumble','grumpier','grumpiest',
						'grumpily','grumpish','grumpy','guile','guilt','guiltily','guilty','gullible','gutless',
						'gutter','hack','hacks','haggard','haggle','hairloss','halfhearted','halfheartedly',
						'hallucinate','hallucination','hamper','hampered','handicapped','hang','hangs',
						'haphazard','hapless','harangue','harass','harassed','harasses','harassment',
						'harboring','harbors','hard','hard-hit','hard-line','hard-liner','hardball','harden',
						'hardened','hardheaded','hardhearted','hardliner','hardliners','hardship','hardships',
						'harm','harmed','harmful','harms','harpy','harridan','harried','harrow','harsh',
						'harshly','hasseling','hassle','hassled','hassles','haste','hastily','hasty','hate',
						'hated','hateful','hatefully','hatefulness','hater','haters','hates','hating',
						'hatred','haughtily','haughty','haunt','haunting','havoc','hawkish','haywire',
						'hazard','hazardous','haze','hazy','head-aches','headache','headaches','heartbreaker',
						'heartbreaking','heartbreakingly','heartless','heathen','heavy-handed','heavyhearted',
						'heck','heckle','heckled','heckles','hectic','hedge','hedonistic','heedless','hefty',
						'hegemonism','hegemonistic','hegemony','heinous','hell','hell-bent','hellion','hells',
						'helpless','helplessly','helplessness','heresy','heretic','heretical','hesitant',
						'hestitant','hideous','hideously','hideousness','high-priced','hiliarious','hinder',
						'hindrance','hiss','hissed','hissing','ho-hum','hoard','hoax','hobble','hogs','hollow',
						'hoodium','hoodwink','hooligan','hopeless','hopelessly','hopelessness','horde',
						'horrendous','horrendously','horrible','horrid','horrific','horrified','horrifies',
						'horrify','horrifying','horrifys','hostage','hostile','hostilities','hostility',
						'hotbeds','hothead','hotheaded','hothouse','hubris','huckster','hum','humid',
						'humiliate','humiliating','humiliation','humming','hung','hurt','hurted','hurtful',
						'hurting','hurts','hustler','hype','hypocricy','hypocrisy','hypocrite','hypocrites',
						'hypocritical','hypocritically','hysteria','hysteric','hysterical','hysterically',
						'hysterics','idiocies','idiocy','idiot','idiotic','idiotically','idiots','idle',
						'ignoble','ignominious','ignominiously','ignominy','ignorance','ignorant','ignore',
						'ill-advised','ill-conceived','ill-defined','ill-designed','ill-fated','ill-favored',
						'ill-formed','ill-mannered','ill-natured','ill-sorted','ill-tempered','ill-treated',
						'ill-treatment','ill-usage','ill-used','illegal','illegally','illegitimate','illicit',
						'illiterate','illness','illogic','illogical','illogically','illusion','illusions',
						'illusory','imaginary','imbalance','imbecile','imbroglio','immaterial','immature',
						'imminence','imminently','immobilized','immoderate','immoderately','immodest',
						'immoral','immorality','immorally','immovable','impair','impaired','impasse',
						'impatience','impatient','impatiently','impeach','impedance','impede','impediment',
						'impending','impenitent','imperfect','imperfection','imperfections','imperfectly',
						'imperialist','imperil','imperious','imperiously','impermissible','impersonal',
						'impertinent','impetuous','impetuously','impiety','impinge','impious','implacable',
						'implausible','implausibly','implicate','implication','implode','impolite','impolitely',
						'impolitic','importunate','importune','impose','imposers','imposing','imposition',
						'impossible','impossiblity','impossibly','impotent','impoverish','impoverished',
						'impractical','imprecate','imprecise','imprecisely','imprecision','imprison',
						'imprisonment','improbability','improbable','improbably','improper','improperly',
						'impropriety','imprudence','imprudent','impudence','impudent','impudently','impugn',
						'impulsive','impulsively','impunity','impure','impurity','inability','inaccuracies',
						'inaccuracy','inaccurate','inaccurately','inaction','inactive','inadequacy',
						'inadequate','inadequately','inadverent','inadverently','inadvisable','inadvisably',
						'inane','inanely','inappropriate','inappropriately','inapt','inaptitude','inarticulate',
						'inattentive','inaudible','incapable','incapably','incautious','incendiary','incense',
						'incessant','incessantly','incite','incitement','incivility','inclement','incognizant',
						'incoherence','incoherent','incoherently','incommensurate','incomparable',
						'incomparably','incompatability','incompatibility','incompatible','incompetence',
						'incompetent','incompetently','incomplete','incompliant','incomprehensible',
						'incomprehension','inconceivable','inconceivably','incongruous','incongruously',
						'inconsequent','inconsequential','inconsequentially','inconsequently','inconsiderate',
						'inconsiderately','inconsistence','inconsistencies','inconsistency','inconsistent',
						'inconsolable','inconsolably','inconstant','inconvenience','inconveniently',
						'incorrect','incorrectly','incorrigible','incorrigibly','incredulous','incredulously',
						'inculcate','indecency','indecent','indecently','indecision','indecisive',
						'indecisively','indecorum','indefensible','indelicate','indeterminable',
						'indeterminably','indeterminate','indifference','indifferent','indigent','indignant',
						'indignantly','indignation','indignity','indiscernible','indiscreet','indiscreetly',
						'indiscretion','indiscriminate','indiscriminately','indiscriminating',
						'indistinguishable','indoctrinate','indoctrination','indolent','indulge','ineffective',
						'ineffectively','ineffectiveness','ineffectual','ineffectually','ineffectualness',
						'inefficacious','inefficacy','inefficiency','inefficient','inefficiently','inelegance',
						'inelegant','ineligible','ineloquent','ineloquently','inept','ineptitude','ineptly',
						'inequalities','inequality','inequitable','inequitably','inequities','inescapable',
						'inescapably','inessential','inevitable','inevitably','inexcusable','inexcusably',
						'inexorable','inexorably','inexperience','inexperienced','inexpert','inexpertly',
						'inexpiable','inexplainable','inextricable','inextricably','infamous','infamously',
						'infamy','infected','infection','infections','inferior','inferiority','infernal',
						'infest','infested','infidel','infidels','infiltrator','infiltrators','infirm',
						'inflame','inflammation','inflammatory','inflammed','inflated','inflationary',
						'inflexible','inflict','infraction','infringe','infringement','infringements',
						'infuriate','infuriated','infuriating','infuriatingly','inglorious','ingrate',
						'ingratitude','inhibit','inhibition','inhospitable','inhospitality','inhuman',
						'inhumane','inhumanity','inimical','inimically','iniquitous','iniquity','injudicious',
						'injure','injurious','injury','injustice','injustices','innuendo','inoperable',
						'inopportune','inordinate','inordinately','insane','insanely','insanity','insatiable',
						'insecure','insecurity','insensible','insensitive','insensitively','insensitivity',
						'insidious','insidiously','insignificance','insignificant','insignificantly',
						'insincere','insincerely','insincerity','insinuate','insinuating','insinuation',
						'insociable','insolence','insolent','insolently','insolvent','insouciance',
						'instability','instable','instigate','instigator','instigators','insubordinate',
						'insubstantial','insubstantially','insufferable','insufferably','insufficiency',
						'insufficient','insufficiently','insular','insult','insulted','insulting',
						'insultingly','insults','insupportable','insupportably','insurmountable',
						'insurmountably','insurrection','intefere','inteferes','intense','interfere',
						'interference','interferes','intermittent','interrupt','interruption','interruptions',
						'intimidate','intimidating','intimidatingly','intimidation','intolerable',
						'intolerablely','intolerance','intoxicate','intractable','intransigence','intransigent',
						'intrude','intrusion','intrusive','inundate','inundated','invader','invalid',
						'invalidate','invalidity','invasive','invective','inveigle','invidious','invidiously',
						'invidiousness','invisible','involuntarily','involuntary','irascible','irate',
						'irately','ire','irk','irked','irking','irks','irksome','irksomely','irksomeness',
						'irksomenesses','ironic','ironical','ironically','ironies','irony','irragularity',
						'irrational','irrationalities','irrationality','irrationally','irrationals',
						'irreconcilable','irrecoverable','irrecoverableness','irrecoverablenesses',
						'irrecoverably','irredeemable','irredeemably','irreformable','irregular',
						'irregularity','irrelevance','irrelevant','irreparable','irreplacible','irrepressible',
						'irresolute','irresolvable','irresponsible','irresponsibly','irretating',
						'irretrievable','irreversible','irritable','irritably','irritant','irritate',
						'irritated','irritating','irritation','irritations','isolate','isolated','isolation',
						'issue','issues','itch','itching','itchy','jabber','jaded','jagged','jam','jarring',
						'jaundiced','jealous','jealously','jealousness','jealousy','jeer','jeering',
						'jeeringly','jeers','jeopardize','jeopardy','jerk','jerky','jitter','jitters',
						'jittery','job-killing','jobless','joke','joker','jolt','judder','juddering',
						'judders','jumpy','junk','junky','junkyard','jutter','jutters','kaput','kill',
						'killed','killer','killing','killjoy','kills','knave','knife','knock','knotted',
						'kook','kooky','lack','lackadaisical','lacked','lackey','lackeys','lacking',
						'lackluster','lacks','laconic','lag','lagged','lagging','laggy','lags','laid-off',
						'lambast','lambaste','lame','lame-duck','lament','lamentable','lamentably',
						'languid','languish','languor','languorous','languorously','lanky','lapse',
						'lapsed','lapses','lascivious','last-ditch','latency','laughable','laughably',
						'laughingstock','lawbreaker','lawbreaking','lawless','lawlessness','layoff',
						'layoff-happy','lazy','leak','leakage','leakages','leaking','leaks','leaky',
						'lech','lecher','lecherous','lechery','leech','leer','leery','left-leaning',
						'lemon','lengthy','less-developed','lesser-known','letch','lethal','lethargic',
						'lethargy','lewd','lewdly','lewdness','liability','liable','liar','liars',
						'licentious','licentiously','licentiousness','lie','lied','lier','lies',
						'life-threatening','lifeless','limit','limitation','limitations','limited',
						'limits','limp','listless','litigious','little-known','livid','lividly','loath',
						'loathe','loathing','loathly','loathsome','loathsomely','lone','loneliness',
						'lonely','loner','lonesome','long-time','long-winded','longing','longingly',
						'loophole','loopholes','loose','loot','lorn','lose','loser','losers','loses',
						'losing','loss','losses','lost','loud','louder','lousy','loveless','lovelorn',
						'low-rated','lowly','ludicrous','ludicrously','lugubrious','lukewarm','lull',
						'lumpy','lunatic','lunaticism','lurch','lure','lurid','lurk','lurking','lying',
						'macabre','mad','madden','maddening','maddeningly','madder','madly','madman',
						'madness','maladjusted','maladjustment','malady','malaise','malcontent',
						'malcontented','maledict','malevolence','malevolent','malevolently','malice',
						'malicious','maliciously','maliciousness','malign','malignant','malodorous',
						'maltreatment','mangle','mangled','mangles','mangling','mania','maniac','maniacal',
						'manic','manipulate','manipulation','manipulative','manipulators','marginal',
						'marginally','martyrdom','martyrdom-seeking','mashed','massacre','massacres','matte',
						'mawkish','mawkishly','mawkishness','meager','meaningless','meanness','measly',
						'meddle','meddlesome','mediocre','mediocrity','melancholy','melodramatic',
						'melodramatically','meltdown','menace','menacing','menacingly','mendacious',
						'mendacity','menial','merciless','mercilessly','mess','messed','messes','messing',
						'messy','midget','miff','militancy','mindless','mindlessly','mirage','mire','misalign',
						'misaligned','misaligns','misapprehend','misbecome','misbecoming','misbegotten','misbehave',
						'misbehavior','miscalculate','miscalculation','miscellaneous','mischief','mischievous',
						'mischievously','misconception','misconceptions','miscreant','miscreants','misdirection',
						'miser','miserable','miserableness','miserably','miseries','miserly','misery','misfit',
						'misfortune','misgiving','misgivings','misguidance','misguide','misguided','mishandle',
						'mishap','misinform','misinformed','misinterpret','misjudge','misjudgment','mislead',
						'misleading','misleadingly','mislike','mismanage','mispronounce','mispronounced','mispronounces',
						'misread','misreading','misrepresent','misrepresentation','miss','missed','misses','misstatement',
						'mist','mistake','mistaken','mistakenly','mistakes','mistified','mistress','mistrust',
						'mistrustful','mistrustfully','mists','misunderstand','misunderstanding','misunderstandings',
						'misunderstood','misuse','moan','mobster','mock','mocked','mockeries','mockery','mocking',
						'mockingly','mocks','molest','molestation','monotonous','monotony','monster','monstrosities',
						'monstrosity','monstrous','monstrously','moody','moot','mope','morbid','morbidly','mordant',
						'mordantly','moribund','moron','moronic','morons','mortification','mortified','mortify',
						'mortifying','motionless','motley','mourn','mourner','mournful','mournfully','muddle','muddy',
						'mudslinger','mudslinging','mulish','multi-polarization','mundane','murder','murderer',
						'murderous','murderously','murky','muscle-flexing','mushy','musty','nag','nagging','naive',
						'naively','narrower','nastily','nastiness','nasty','naughty','nauseate','nauseates','nauseating',
						'nauseatingly','naÔve','nebulous','nebulously','needless','needlessly','needy','nefarious',
						'nefariously','negate','negation','negative','negatives','negativity','neglect','neglected',
						'negligence','negligent','nemesis','nepotism','nervous','nervously','nervousness','nettle',
						'nettlesome','neurotic','neurotically','niggle','niggles','nightmare','nightmarish','nightmarishly',
						'nitpick','nitpicking','noise','noises','noisier','noisy','non-confidence','nonexistent',
						'nonresponsive','nonsense','nosey','notoriety','notorious','notoriously','noxious','nuisance',
						'numb','obese','object','objection','objectionable','objections','oblique','obliterate',
						'obliterated','oblivious','obnoxious','obnoxiously','obscene','obscenely','obscenity','obscure',
						'obscured','obscures','obscurity','obsess','obsessive','obsessively','obsessiveness','obsolete',
						'obstacle','obstinate','obstinately','obstruct','obstructed','obstructing','obstruction',
						'obstructs','obtrusive','obtuse','occlude','occluded','occludes','occluding','odd','odder',
						'oddest','oddities','oddity','oddly','odor','offence','offend','offender','offending','offenses',
						'offensive','offensively','offensiveness','officious','ominous','ominously','omission','omit',
						'one-sided','onerous','onerously','onslaught','opinionated','opponent','opportunistic','oppose',
						'opposition','oppositions','oppress','oppression','oppressive','oppressively','oppressiveness',
						'oppressors','ordeal','orphan','ostracize','outbreak','outburst','outbursts','outcast','outcry',
						'outlaw','outmoded','outrage','outraged','outrageous','outrageously','outrageousness','outrages',
						'outsider','over-acted','over-awe','over-balanced','over-hyped','over-priced','over-valuation',
						'overact','overacted','overawe','overbalance','overbalanced','overbearing','overbearingly',
						'overblown','overdo','overdone','overdue','overemphasize','overheat','overkill','overloaded',
						'overlook','overpaid','overpayed','overplay','overpower','overpriced','overrated','overreach',
						'overrun','overshadow','oversight','oversights','oversimplification','oversimplified','oversimplify',
						'oversize','overstate','overstated','overstatement','overstatements','overstates','overtaxed',
						'overthrow','overthrows','overturn','overweight','overwhelm','overwhelmed','overwhelming',
						'overwhelmingly','overwhelms','overzealous','overzealously','overzelous','pain','painful',
						'painfull','painfully','pains','pale','pales','paltry','pan','pandemonium','pander','pandering',
						'panders','panic','panick','panicked','panicking','panicky','paradoxical','paradoxically',
						'paralize','paralyzed','paranoia','paranoid','parasite','pariah','parody','partiality',
						'partisan','partisans','passe','passive','passiveness','pathetic','pathetically','patronize',
						'paucity','pauper','paupers','payback','peculiar','peculiarly','pedantic','peeled','peeve',
						'peeved','peevish','peevishly','penalize','penalty','perfidious','perfidity','perfunctory',
						'peril','perilous','perilously','perish','pernicious','perplex','perplexed','perplexing',
						'perplexity','persecute','persecution','pertinacious','pertinaciously','pertinacity','perturb',
						'perturbed','pervasive','perverse','perversely','perversion','perversity','pervert','perverted',
						'perverts','pessimism','pessimistic','pessimistically','pest','pestilent','petrified','petrify',
						'pettifog','petty','phobia','phobic','phony','picket','picketed','picketing','pickets','picky',
						'pig','pigs','pillage','pillory','pimple','pinch','pique','pitiable','pitiful','pitifully',
						'pitiless','pitilessly','pittance','pity','plagiarize','plague','plasticky','plaything','plea',
						'pleas','plebeian','plight','plot','plotters','ploy','plunder','plunderer','pointless',
						'pointlessly','poison','poisonous','poisonously','pokey','poky','polarisation','polemize',
						'pollute','polluter','polluters','polution','pompous','poor','poorer','poorest','poorly',
						'posturing','pout','poverty','powerless','prate','pratfall','prattle','precarious','precariously',
						'precipitate','precipitous','predatory','predicament','prejudge','prejudice','prejudices',
						'prejudicial','premeditated','preoccupy','preposterous','preposterously','presumptuous',
						'presumptuously','pretence','pretend','pretense','pretentious','pretentiously','prevaricate',
						'pricey','pricier','prick','prickle','prickles','prideful','prik','primitive','prison','prisoner',
						'problem','problematic','problems','procrastinate','procrastinates','procrastination','profane',
						'profanity','prohibit','prohibitive','prohibitively','propaganda','propagandize','proprietary',
						'prosecute','protest','protested','protesting','protests','protracted','provocation','provocative',
						'provoke','pry','pugnacious','pugnaciously','pugnacity','punch','punish','punishable','punitive',
						'punk','puny','puppet','puppets','puzzled','puzzlement','puzzling','quack','qualm','qualms',
						'quandary','quarrel','quarrellous','quarrellously','quarrels','quarrelsome','quash','queer',
						'questionable','quibble','quibbles','quitter','rabid','racism','racist','racists','racy',
						'radical','radicalization','radically','radicals','rage','ragged','raging','rail','raked',
						'rampage','rampant','ramshackle','rancor','randomly','rankle','rant','ranted','ranting',
						'rantingly','rants','rape','raped','raping','rascal','rascals','rash','rattle','rattled',
						'rattles','ravage','raving','reactionary','rebellious','rebuff','rebuke','recalcitrant',
						'recant','recession','recessionary','reckless','recklessly','recklessness','recoil','recourses',
						'redundancy','redundant','refusal','refuse','refused','refuses','refusing','refutation',
						'refute','refuted','refutes','refuting','regress','regression','regressive','regret','regreted',
						'regretful','regretfully','regrets','regrettable','regrettably','regretted','reject','rejected',
						'rejecting','rejection','rejects','relapse','relentless','relentlessly','relentlessness',
						'reluctance','reluctant','reluctantly','remorse','remorseful','remorsefully','remorseless',
						'remorselessly','remorselessness','renounce','renunciation','repel','repetitive','reprehensible',
						'reprehensibly','reprehension','reprehensive','repress','repression','repressive','reprimand',
						'reproach','reproachful','reprove','reprovingly','repudiate','repudiation','repugn','repugnance',
						'repugnant','repugnantly','repulse','repulsed','repulsing','repulsive','repulsively','repulsiveness',
						'resent','resentful','resentment','resignation','resigned','resistance','restless','restlessness',
						'restrict','restricted','restriction','restrictive','resurgent','retaliate','retaliatory','retard',
						'retarded','retardedness','retards','reticent','retract','retreat','retreated','revenge','revengeful',
						'revengefully','revert','revile','reviled','revoke','revolt','revolting','revoltingly','revulsion',
						'revulsive','rhapsodize','rhetoric','rhetorical','ricer','ridicule','ridicules','ridiculous',
						'ridiculously','rife','rift','rifts','rigid','rigidity','rigidness','rile','riled','rip','rip-off',
						'ripoff','ripped','risk','risks','risky','rival','rivalry','roadblocks','rocky','rogue',
						'rot','rotten','rough','rremediable','rubbish','rude','rue','ruffian','ruffle','ruin','ruined',
						'ruining','ruinous','ruins','rumbling','rumor','rumors','rumours','rumple','run-down','runaway',
						'rupture','rust','rusts','rusty','rut','ruthless','ruthlessly','ruthlessness','ruts','sabotage',
						'sack','sacrificed','sad','sadden','sadly','sadness','sag','sagged','sagging','saggy','sags',
						'salacious','sanctimonious','sap','sarcasm','sarcastic','sarcastically','sardonic','sardonically',
						'sass','satirical','satirize','savage','savaged','savagery','savages','scaly','scam','scams',
						'scandal','scandalize','scandalized','scandalous','scandalously','scandals','scandel','scandels',
						'scant','scapegoat','scar','scarce','scarcely','scarcity','scare','scared','scarier','scariest',
						'scarily','scarred','scars','scary','scathing','scathingly','sceptical','scoff','scoffingly',
						'scold','scolded','scolding','scoldingly','scorching','scorchingly','scorn','scornful','scornfully',
						'scoundrel','scourge','scowl','scramble','scrambled','scrambles','scrambling','scrap','scratch',
						'scratched','scratches','scratchy','scream','screech','screw-up','screwed','screwed-up','screwy',
						'scuff','scuffs','scum','scummy','second-class','second-tier','secretive','sedentary','seedy',
						'seethe','seething','self-coup','self-criticism','self-defeating','self-destructive',
						'self-humiliation','self-interest','self-interested','self-serving','selfinterested','selfish',
						'selfishly','selfishness','semi-retarded','senile','sensationalize','senseless','senselessly',
						'seriousness','sermonize','servitude','set-up','setback','setbacks','sever','severe','severity',
						'sh*t','shabby','shadowy','shady','shake','shaky','shallow','sham','shambles','shame','shameful',
						'shamefully','shamefulness','shameless','shamelessly','shamelessness','shark','sharply','shatter',
						'shemale','shimmer','shimmy','shipwreck','shirk','shirker','shit','shiver','shock','shocked',
						'shocking','shockingly','shoddy','short-lived','shortage','shortchange','shortcoming','shortcomings',
						'shortness','shortsighted','shortsightedness','showdown','shrew','shriek','shrill','shrilly',
						'shrivel','shroud','shrouded','shrug','shun','shunned','sick','sicken','sickening','sickeningly',
						'sickly','sickness','sidetrack','sidetracked','siege','sillily','silly','simplistic','simplistically',
						'sin','sinful','sinfully','sinister','sinisterly','sink','sinking','skeletons','skeptic','skeptical',
						'skeptically','skepticism','sketchy','skimpy','skinny','skittish','skittishly','skulk','slack',
						'slander','slanderer','slanderous','slanderously','slanders','slap','slashing','slaughter',
						'slaughtered','slave','slaves','sleazy','slime','slog','slogged','slogging','slogs',
						'sloooow','slooow','sloow','sloppily','sloppy','sloth','slothful',
						'slow','slow-moving','slowed','slower','slowest','slowly','sloww','slowww','slowwww','slug',
						'sluggish','slump','slumping','slumpping','slur','slut','sluts','sly','smack','smallish','smash',
						'smear','smell','smelled','smelling','smells','smelly','smelt','smoke','smokescreen','smolder',
						'smoldering','smother','smoulder','smouldering','smudge','smudged','smudges','smudging','smug',
						'smugly','smut','smuttier','smuttiest','smutty','snag','snagged','snagging','snags','snappish',
						'snappishly','snare','snarky','snarl','sneak','sneakily','sneaky','sneer','sneering','sneeringly',
						'snob','snobbish','snobby','snobish','snobs','snub','so-cal','soapy','sob','sober','sobering',
						'solemn','solicitude','somber','sore','sorely','soreness','sorrow','sorrowful','sorrowfully',
						'sorry','sour','sourly','spade','spank','spendy','spew','spewed','spewing','spews','spilling',
						'spinster','spiritless','spite','spiteful','spitefully','spitefulness','splatter','split',
						'splitting','spoil','spoilage','spoilages','spoiled','spoilled','spoils','spook','spookier',
						'spookiest','spookily','spooky','spoon-fed','spoon-feed','spoonfed','sporadic','spotty','spurious',
						'spurn','sputter','squabble','squabbling','squander','squash','squeak','squeaks','squeaky',
						'squeal','squealing','squeals','squirm','stab','stagnant','stagnate','stagnation','staid','stain',
						'stains','stale','stalemate','stall','stalls','stammer','stampede','standstill','stark','starkly',
						'startle','startling','startlingly','starvation','starve','static','steal','stealing','steals',
						'steep','steeply','stench','stereotype','stereotypical','stereotypically','stern','stew','sticky',
						'stiff','stiffness','stifle','stifling','stiflingly','stigma','stigmatize','sting','stinging',
						'stingingly','stingy','stink','stinks','stodgy','stole','stolen','stooge','stooges','stormy',
						'straggle','straggler','strain','strained','straining','strange','strangely','stranger','strangest',
						'strangle','streaky','strenuous','stress','stresses','stressful','stressfully','stricken','strict',
						'strictly','strident','stridently','strife','strike','stringent','stringently','struck','struggle',
						'struggled','struggles','struggling','strut','stubborn','stubbornly','stubbornness','stuck','stuffy',
						'stumble','stumbled','stumbles','stump','stumped','stumps','stun','stunt','stunted','stupid',
						'stupidest','stupidity','stupidly','stupified','stupify','stupor','stutter','stuttered','stuttering',
						'stutters','sty','stymied','sub-par','subdued','subjected','subjection','subjugate','subjugation',
						'submissive','subordinate','subpoena','subpoenas','subservience','subservient','substandard',
						'subtract','subversion','subversive','subversively','subvert','succumb','suck','sucked','sucker',
						'sucks','sucky','sue','sued','sueing','sues','suffer','suffered','sufferer','sufferers','suffering',
						'suffers','suffocate','sugar-coat','sugar-coated','sugarcoated','suicidal','suicide','sulk',
						'sullen','sully','sunder','sunk','sunken','superficial','superficiality','superficially',
						'superfluous','superstition','superstitious','suppress','suppression','surrender','susceptible',
						'suspect','suspicion','suspicions','suspicious','suspiciously','swagger','swamped','sweaty',
						'swelled','swelling','swindle','swipe','swollen','symptom','symptoms','syndrome','taboo','tacky',
						'taint','tainted','tamper','tangle','tangled','tangles','tank','tanked','tanks','tantrum',
						'tardy','tarnish','tarnished','tarnishes','tarnishing','tattered','taunt','taunting','tauntingly',
						'taunts','taut','tawdry','taxing','tease','teasingly','tedious','tediously','temerity','temper',
						'tempest','temptation','tenderness','tense','tension','tentative','tentatively','tenuous',
						'tenuously','tepid','terrible','terribleness','terribly','terror','terror-genic','terrorism',
						'terrorize','testily','testy','tetchily','tetchy','thankless','thicker','thirst','thorny',
						'thoughtless','thoughtlessly','thoughtlessness','thrash','threat','threaten','threatening','threats',
						'threesome','throb','throbbed','throbbing','throbs','throttle','thug','thumb-down','thumbs-down',
						'thwart','time-consuming','timid','timidity','timidly','timidness','tin-y','tingled','tingling',
						'tired','tiresome','tiring','tiringly','toil','toll','top-heavy','topple','torment','tormented',
						'torrent','tortuous','torture','tortured','tortures','torturing','torturous','torturously',
						'totalitarian','touchy','toughness','tout','touted','touts','toxic','traduce','tragedy','tragic',
						'tragically','traitor','traitorous','traitorously','tramp','trample','transgress','transgression',
						'trap','traped','trapped','trash','trashed','trashy','trauma','traumatic','traumatically',
						'traumatize','traumatized','travesties','travesty','treacherous','treacherously','treachery',
						'treason','treasonous','trick','tricked','trickery','tricky','trivial','trivialize','trouble',
						'troubled','troublemaker','troubles','troublesome','troublesomely','troubling','troublingly',
						'truant','tumble','tumbled','tumbles','tumultuous','turbulent','turmoil','twist','twisted',
						'twists','two-faced','two-faces','tyrannical','tyrannically','tyranny','tyrant','ugh','uglier',
						'ugliest','ugliness','ugly','ulterior','ultimatum','ultimatums','ultra-hardline','un-viewable',
						'unable','unacceptable','unacceptablely','unacceptably','unaccessible','unaccustomed','unachievable',
						'unaffordable','unappealing','unattractive','unauthentic','unavailable','unavoidably','unbearable',
						'unbearablely','unbelievable','unbelievably','uncaring','uncertain','uncivil','uncivilized','unclean',
						'unclear','uncollectible','uncomfortable','uncomfortably','uncomfy','uncompetitive','uncompromising',
						'uncompromisingly','unconfirmed','unconstitutional','uncontrolled','unconvincing','unconvincingly',
						'uncooperative','uncouth','uncreative','undecided','undefined','undependability','undependable',
						'undercut','undercuts','undercutting','underdog','underestimate','underlings','undermine',
						'undermined','undermines','undermining','underpaid','underpowered','undersized','undesirable',
						'undetermined','undid','undignified','undissolved','undocumented','undone','undue','unease',
						'uneasily','uneasiness','uneasy','uneconomical','unemployed','unequal','unethical','uneven',
						'uneventful','unexpected','unexpectedly','unexplained','unfairly','unfaithful','unfaithfully',
						'unfamiliar','unfavorable','unfeeling','unfinished','unfit','unforeseen','unforgiving','unfortunate',
						'unfortunately','unfounded','unfriendly','unfulfilled','unfunded','ungovernable','ungrateful',
						'unhappily','unhappiness','unhappy','unhealthy','unhelpful','unilateralism','unimaginable',
						'unimaginably','unimportant','uninformed','uninsured','unintelligible','unintelligile','unipolar',
						'unjust','unjustifiable','unjustifiably','unjustified','unjustly','unkind','unkindly','unknown',
						'unlamentable','unlamentably','unlawful','unlawfully','unlawfulness','unleash','unlicensed',
						'unlikely','unlucky','unmoved','unnatural','unnaturally','unnecessary','unneeded','unnerve',
						'unnerved','unnerving','unnervingly','unnoticed','unobserved','unorthodox','unorthodoxy',
						'unpleasant','unpleasantries','unpopular','unpredictable','unprepared','unproductive','unprofitable',
						'unprove','unproved','unproven','unproves','unproving','unqualified','unravel','unraveled',
						'unreachable','unreadable','unrealistic','unreasonable','unreasonably','unrelenting','unrelentingly',
						'unreliability','unreliable','unresolved','unresponsive','unrest','unruly','unsafe','unsatisfactory',
						'unsavory','unscrupulous','unscrupulously','unsecure','unseemly','unsettle','unsettled','unsettling',
						'unsettlingly','unskilled','unsophisticated','unsound','unspeakable','unspeakablely','unspecified',
						'unstable','unsteadily','unsteadiness','unsteady','unsuccessful','unsuccessfully','unsupported',
						'unsupportive','unsure','unsuspecting','unsustainable','untenable','untested','unthinkable',
						'unthinkably','untimely','untouched','untrue','untrustworthy','untruthful','unusable','unusably',
						'unuseable','unuseably','unusual','unusually','unviewable','unwanted','unwarranted','unwatchable',
						'unwelcome','unwell','unwieldy','unwilling','unwillingly','unwillingness','unwise','unwisely',
						'unworkable','unworthy','unyielding','upbraid','upheaval','uprising','uproar','uproarious',
						'uproariously','uproarous','uproarously','uproot','upset','upseting','upsets','upsetting',
						'upsettingly','urgent','useless','usurp','usurper','utterly','vagrant','vague','vagueness','vain',
						'vainly','vanity','vehement','vehemently','vengeance','vengeful','vengefully','vengefulness',
						'venom','venomous','venomously','vent','vestiges','vex','vexation','vexing','vexingly','vibrate',
						'vibrated','vibrates','vibrating','vibration','vice','vicious','viciously','viciousness',
						'victimize','vile','vileness','vilify','villainous','villainously','villains','villian',
						'villianous','villianously','villify','vindictive','vindictively','vindictiveness','violate',
						'violation','violator','violators','violent','violently','viper','virulence','virulent',
						'virulently','virus','vociferous','vociferously','volatile','volatility','vomit','vomited',
						'vomiting','vomits','vulgar','vulnerable','wack','wail','wallow','wane','waning','wanton',
						'war-like','warily','wariness','warlike','warned','warning','warp','warped','wary','washed-out',
						'waste','wasted','wasteful','wastefulness','wasting','water-down','watered-down','wayward','weak',
						'weaken','weakening','weaker','weakness','weaknesses','weariness','wearisome','weary','wedge',
						'weed','weep','weird','weirdly','wheedle','whimper','whine','whining','whiny','whips','whore',
						'whores','wicked','wickedly','wickedness','wild','wildly','wiles','wilt','wily','wimpy','wince',
						'wobble','wobbled','wobbles','woe','woebegone','woeful','woefully','womanizer','womanizing',
						'worn','worried','worriedly','worrier','worries','worrisome','worry','worrying','worryingly',
						'worse','worsen','worsening','worst','worthless','worthlessly','worthlessness','wound','wounds',
						'wrangle','wrath','wreak','wreaked','wreaks','wreck','wrest','wrestle','wretch','wretched',
						'wretchedly','wretchedness','wrinkle','wrinkled','wrinkles','wrip','wripped','wripping',
						'writhe','wrong','wrongful','wrongly','wrought','yawn','zap','zapped','zaps','zealot','zealous',
						'zealously','zombie');
			

			$weight_positive = 0;
			$weight_negative = 0;
			$weight_aggressive = 0;
			$weight_csv = 0;
			
			/*
				positive_strict
				aggressive_strict
				positive_weighed
				negative_weighed
			*/
			
			switch($filter) {

				case 'csv':
					
				if(strlen($csv_filter) > 3) {
					
					$words_csv = explode(',',$csv_filter);
					
					if(is_array($words_csv)) {
						
						foreach($words_csv as $key=>$value) { 
							if(stristr($string, $value)) {
								$weight_csv++;
								break;
							}
						}
					
						if($weight_csv >= 1) {
								return true;
						}	

					}		
				}				
				
				break;
				
				case 'positive_strict':
				
					foreach($words_negative as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_negative++;
							break;
						}
					}
				
					if($weight_negative >= 1) {
							return true;
					}		
				
				break;
				
				case 'aggressive_strict':
				
					foreach($words_aggressive as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_aggressive++;
							break;
						}
					}
				
					if($weight_aggressive >= 1) {
							return true;
					}		
					
				break;			
				
				case 'positive_weighed':
				
					foreach($words_positive as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_positive++;
						}
					}

					foreach($words_negative as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_negative++;
						}
					}
				
					if($weight_positive > 1) {
						if($weight_positive > $weight_negative) {
								return true;
						}
					}

				break;
				
				case 'negative_weighed':
				
					foreach($words_positive as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_positive++;
						}
					}

					foreach($words_negative as $key=>$value) { 
						if(stristr($string, $value)) {
							$weight_negative++;
						}
					}
				
					if($weight_negative > 1) {
						if($weight_negative > $weight_positive) {
								return true;
						}
					}

				break;			
				
				
			}
			
		}
	}


	$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

	function clean($string,$method) {
		
		switch($method) {
			case 'description':
				$string = substr(htmlspecialchars(strip_tags($string),ENT_QUOTES,'UTF-8'),0,255)."...";
			break;
			case 'title':
				$string = ucfirst(strtolower(substr(htmlspecialchars($string,ENT_QUOTES,'UTF-8'),0,255)));
			break;	
			case 'url':
				$string = htmlspecialchars(strip_tags($string),ENT_QUOTES,'UTF-8');
			break;	
			
		}
		
		return $string;
	}

	function checkfeed($value) {

		if(is_array($value)) {
			return false;
		}
			
		if($value == 'Array') {
			return false;
		}
		
		return true;
	}

	foreach($feeds as $key => $value) {
		
		$xml = file_get_contents($value, false, $context);

		$xml = simplexml_load_string($xml);
		$json_string = json_encode($xml);
		$result_array = json_decode($json_string, TRUE);

			$i=0;
			
			if(!empty($result_array['channel']['item'][$i]['description'])) { 

				echo "<div class=\"container\"><h4>".clean($value,'description')."</h4>";
				
				$counter = count($result_array['channel']['item']);
				
				for($i;$i<$maxarticles;$i++) {
					
					if(!empty($counter)) {
						
						$link = $result_array['channel']['item'][$i]['link'];
						$title = $result_array['channel']['item'][$i]['title'];
						$desc = $result_array['channel']['item'][$i]['description'];
						
						$check_link  = checkfeed($link);
						$check_title = checkfeed($title);
						$check_desc  = checkfeed($desc);
						
						
						if($check_link != false && $check_title != false && $check_desc != false) { 
						
							if(filter($desc,$global_filter,$csv_filter) == true) {
								
									echo "<a href='".clean($link,'url')."' class='rss_link'>".clean($title,'title')."</a>";
									
									if($global_filter == 'positive_strict') {
										echo "<span class='description-filtered-positive'>".clean($desc,'description')."</span>";
									} elseif($global_filter == 'positive_weighed') {
										echo "<span class='description-filtered-positive'>".clean($desc,'description')."</span>";
									} elseif($global_filter == 'csv') {
										echo "<span class='description-filtered-csv'>".clean($desc,'description')."</span>";
									} else {
										echo "<span class='description-filtered-negative'>".clean($desc,'description')."</span>";
									}
								
								} else {
								echo "<a href='".clean($link,'url')."' class='rss_link'>".clean($title,'title')."</a>";
								echo "<span class='description'>".clean($desc,'description')."</span>";
							}
						}
						
						
					}
					
				}
				
			}
		
		echo "</div>";
	}

	?>
		
	</div>
</body>
	
</html>
