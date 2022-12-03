<script>
    // rss element
    class RssItem  {
        constructor(rssElement, obj) {
            this.obj = obj;
            this.rssElement = rssElement;
        }

        getClassName() {
            return this.obj.attr('rss-item');
        }

        getElements() {
            return this.rssElement.obj.find('[rss-item="'+this.getClassName()+'"]');
        }

        highlight() {
            this.getElements().addClass('rss-highlight');
        }

        removeHighlight() {
            this.getElements().removeClass('rss-highlight');
        }

        select() {
            this.getElements().addClass('rss-selected');
            this.obj.find('textarea').focus();
        }

        unselect() {
            this.getElements().removeClass('rss-selected');
        }
    }

    class FeedTitleRssItem extends RssItem {
        selector() {
            return ".feed-title-rss-item";
        }
    }
</script>