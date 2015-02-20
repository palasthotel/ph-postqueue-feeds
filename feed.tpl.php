<?php 
header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?><rss 
version="2.0" 
xmlns:content="http://purl.org/rss/1.0/modules/content/" 
xmlns:wfw="http://wellformedweb.org/CommentAPI/" 
xmlns:dc="http://purl.org/dc/elements/1.1/" 
xmlns:atom="http://www.w3.org/2005/Atom" 
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" 
xmlns:slash="http://purl.org/rss/1.0/modules/slash/" >
  <channel>
    <title>Postqueue Name</title>
    <link>Startseite?</link>
   <!--  <description>Postqueue Name</description> -->
    <language>de-DE</language>
    <lastBuildDate>Fri, 13 Feb 2015 06:01:17 +0000</lastBuildDate>
    <generator>http://wordpress.org/plugins/ph-postqueue-fees/?v=1.0</generator>
    <?php 
    foreach ($posts as $post) { 
      $wp_post = get_post($post->post_id);
    ?>
      <item>
        <title><?php echo $wp_post->post_title; ?></title>
        <link><?php echo get_permalink($post->post_id); ?></link>
        <description><?php echo '<![CDATA['.$wp_post->post_excerpt.'<br/><br/>Keep on reading: <a href="'.get_permalink($post->post_id).'">'.get_the_title($post->post_id).'</a>'.']]>';  ?></description>
        <pubDate><?php echo get_the_date( __("r"), $post->post_id ); ?></pubDate>
        <guid><?php echo get_permalink($post->post_id); ?></guid>
      </item>
    <?php } ?>
  </channel>
</rss>