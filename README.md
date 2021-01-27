# rss-news-reader
RSS News Reader

This is a simple PHP file that fetches and parses about 140+ rss feeds from news websites in realtime, and displays the result. When each stream is retrieved, weights can be added to determine the value of the news content. This weight filter, filters on negative, positive or agressive keywords. The filter then sorts the news according to predetermined wordlists containing these words. A variety of words are added, some without context and may lead to false positives or false negatives. It is therefore advised to check the words in these lists to suit personal preferences. The retrieval of 140 feeds takes about 2 to 3 seconds on an average system, and is retrieved in a real time stream context.

# Filter parameters:

- positive_strict: filters news on strict positive keywords.
- aggressive_strict: filters news on agressive keywords.
- positive_weighed: filters news on positive weighed keywords.
- negative_weighed: filters news on negative weighed keywords.
- CSV: filters news on use given keywords.

# CSV
It is also possible to add CSV values and fetch news articles based upon given keywords.

# RSS List

The RSS list was composed by @Dave Winer: https://github.com/scripting/feedsForJournalists
