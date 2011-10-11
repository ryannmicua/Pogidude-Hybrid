<?php 
/**
 * Example template for use with post thumbnails
 * Requires WordPress 2.9, Hybrid Core, and a theme which supports post thumbnails
 * Author: Pogidude (Ryann Micua)
 **/ 
/**
 * CSS STYLES - copy the following to your stylesheet

	.related-articles{ }
		.related-articles h3{}
		.related-articles ol{ list-style: none outside none; height: 264px; margin: 0; overflow: hidden; }
			.related-articles ol li{ background: #f5f5f5; border: 1px solid #ddd; float: left; margin: 0 10px 10px 0; height: 260px; width: 142px; }
			.related-articles ol li:hover{ border: 1px solid #ccc;}
				.related-articles li .padder{ padding: 6px; }
				.related-articles .thumbnail, .related-articles .no-thumb{ display: block; width: 130px; height: 130px; margin-bottom: 10px; }
				.related-articles .no-thumb{ background: #777; color: #eee; display: block; font-size: 13px; text-align: center; line-height: 120px; text-decoration: none; }
				.related-articles a.post-title{ color: #3b3b3b; font-size: 14px; line-height: 1; text-decoration: none; }
				.related-articles a.post-title:hover{ color: #6F781F; } //color of title on hover
*/

$args = array( 'echo' => false, 'img_class' => 'hehe' );
?>
<?php if ($related_query->have_posts()):?>
<h4>Related Articles</h4>
<ol>
	<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
		<?php $img = get_the_image( $args ); ?>
		<li>
			<div class="padder">
				<?php if( !empty( $img ) ) : ?>
					<?php echo $img; ?>
				<?php else : ?>
					<a class="no-thumb" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">No available image</a>
				<?php endif; ?>
				<a class="post-title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</div>
		</li>

	<?php endwhile; ?>
</ol>

<?php else: ?>

<?php endif; ?>
