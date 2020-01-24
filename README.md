# rss-news-reader
RSS News Reader

This is a simple PHP file that fetches and parses about 140+ rss feeds from news websites in realtime, and displays the result. When each stream is retrieved, weights can be added to determine the value of the news content. This weight filter, filters on negative, positive or agressive keywords. The filter then sorts the news according to predetermined wordlists containing these words. A variety of words are added, some without context and may lead to false positives or false negatives. It is therefore advised to check the words in these lists to suit personal preferences. The retrieval of 140 feeds takes about 2 to 3 seconds on an average system, and is retrieved in a real time stream context.

# Filter parameters:

positive_strict

aggressive_strict

positive_weighed

negative_weighed


Which can be set through the REQUEST param:

reader.php?filter=positive_weighed


The RSS list was composed by @Dave Winer: https://github.com/scripting/feedsForJournalists
