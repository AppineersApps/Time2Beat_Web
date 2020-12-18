<?php
// total page count calculation
$pages = ((int) ceil($total / $rpp));

// if it's an invalid page request
if ($current < 1) {
    return;
} elseif ($current > $pages) {
    return;
}

// if there are pages to be shown
if ($pages > 1 || $alwaysShowPagination === true) {

    ?>
    <ul class="<?php echo implode(' ', $classes) ?>">
        <?php
        /**
         * Previous Link
         */
        // anchor classes and target
        $general_link = 'paging-link';
        $current_link = 'paging-current';
        $anchor_link = 'paging-anchor';

        $classes = array('copy', 'previous');
        $prev_attr = $prev_class = '';
        if ($ajax) {
            $href = 'javascript://';
            if ($current === 1) {
                array_push($classes, 'disabled');
                $prev_class = $general_link . ' ' . $current_link;
            } else {
                $prev_class = $general_link . ' ' . $anchor_link;
                $prev_attr = 'data-page-number="' . ($current - 1) . '"';
            }
        } else {
            $params = $get;
            $params[$key] = ($current - 1);
            $href = ($target) . '?' . http_build_query($params);
            $href = preg_replace(
                array('/=$/', '/=&/'), array('', '&'), $href
            );
            if ($current === 1) {
                $href = '#';
                array_push($classes, 'disabled');
            }
        }

        ?>
        <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href) ?>" class="<?php echo $prev_class; ?>" <?php echo $prev_attr; ?>><?php echo ($previous) ?></a></li>
        <?php
        /**
         * if this isn't a clean output for pagination (eg. show numerical
         * links)
         */
        if (!$clean) {

            /**
             * Calculates the number of leading page crumbs based on the minimum
             *     and maximum possible leading pages.
             */
            $max = min($pages, $crumbs);
            $limit = ((int) floor($max / 2));
            $leading = $limit;
            for ($x = 0; $x < $limit; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - $limit; $x < $pages; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $max - ($pages - $x);
                    break;
                }
            }

            // calculate trailing crumb count based on inverse of leading
            $trailing = $max - $leading - 1;
            $anchor_class = $general_link . ' ' . $anchor_link;
            // generate/render leading crumbs
            for ($x = 0; $x < $leading; ++$x) {

                // class/href setup
                if ($ajax) {
                    $href = 'javascript://';
                } else {
                    $params = $get;
                    $params[$key] = ($current + $x - $leading);
                    $href = ($target) . '?' . http_build_query($params);
                    $href = preg_replace(
                        array('/=$/', '/=&/'), array('', '&'), $href
                    );
                }

                ?>
                <li class="number"><a data-page-number="<?php echo ($current + $x - $leading) ?>" href="<?php echo ($href) ?>" class="<?php echo $anchor_class; ?>"><?php echo ($current + $x - $leading) ?></a></li>
                <?php
            }

            // print current page

            ?>
            <li class="number active"><a data-page-number="<?php echo ($current) ?>" href="javascript://" class="<?php echo $general_link . ' ' . $current_link; ?>"><?php echo ($current) ?></a></li>
            <?php
            // generate/render trailing crumbs
            for ($x = 0; $x < $trailing; ++$x) {

                // class/href setup
                if ($ajax) {
                    $href = 'javascript://';
                } else {
                    $params = $get;
                    $params[$key] = ($current + $x + 1);
                    $href = ($target) . '?' . http_build_query($params);
                    $href = preg_replace(
                        array('/=$/', '/=&/'), array('', '&'), $href
                    );
                }

                ?>
                <li class="number"><a data-page-number="<?php echo ($current + $x + 1) ?>" href="<?php echo ($href) ?>" class="<?php echo $anchor_class; ?>"><?php echo ($current + $x + 1) ?></a></li>
                    <?php
                }
            }

            /**
             * Next Link
             */
            // anchor classes and target
            $classes = array('copy', 'next');
            $next_attr = $next_class = '';
            if ($ajax) {
                $href = 'javascript://';
                if ($current === $pages) {
                    array_push($classes, 'disabled');
                    $next_class = $general_link . ' ' . $current_link;
                } else {
                    $next_class = $general_link . ' ' . $anchor_link;
                    $next_attr = 'data-page-number="' . ($current + 1) . '"';
                }
            } else {
                $params = $get;
                $params[$key] = ($current + 1);
                $href = ($target) . '?' . http_build_query($params);
                $href = preg_replace(
                    array('/=$/', '/=&/'), array('', '&'), $href
                );
                if ($current === $pages) {
                    $href = '#';
                    array_push($classes, 'disabled');
                }
            }

            ?>
        <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href) ?>" class="<?php echo $next_class; ?>" <?php echo $next_attr; ?>><?php echo ($next) ?></a></li>
    </ul>
    <?php
}
