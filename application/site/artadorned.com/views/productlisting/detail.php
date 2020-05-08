<section class="content_container">

    <section class="page_section">
        <?php $maxItemCount = $requestService->getAttribute("maxItemCount"); 
              $firstImage = count($productImages) > 0 ? $productImages[0] : NULL;
        ?>

        <section class="product_details_container">

            <!-- MAIN TOP -->
            <section class="product_details_header">
                <h1 class="title"><?php echo $product->name; ?></h1>
                <h2 class="category_title"><?php echo $collection->name; ?></h2>
                
                <!-- ADDTHIS WIDGET 
                <div class="addthis_sharing_toolbox"></div>
                <div class="fb-share-button" data-href="" data-layout="icon"></div>
                -->
                
                <?php $pUrl = urlencode($navigationService->getItemUrl($product, "Product")); 
                      $pName = urlencode($product->name);
                ?>
                
                <div class="social_buttons">
                    
                    <!-- fb -->
                    <a href="<?php echo $resourceService->getConfig("social.share.facebook"); ?>?u=<?php echo $pUrl; ?>&title=<?php echo $pName; ?>"target="_blank">
                        <i class="icon-facebook"></i><span>Facebook</span>
                    </a>
                    
                    <!-- twitter -->
                    <a href="<?php echo $resourceService->getConfig("social.share.twitter"); ?>?status=<?php echo $pName; ?>+<?php echo $pUrl; ?>" target="_blank">
                        <i class="icon-twitter"></i><span>Twitter</span>
                    </a>
                    
                    <!-- google + -->
                    <a href="<?php echo $resourceService->getConfig("social.share.googleplus"); ?>?url=<?php echo $pUrl; ?>"  target="_blank"><i class="icon-google-plus"></i> <span>Google +</span></a>
                    
                    <!-- pinterest -->
                    <a href="<?php echo $resourceService->getConfig("social.share.pinterest"); ?>?url=<?php echo $pUrl;?>&media=<?php echo urlencode($firstImage ? $resourceService->getAssetUrl($firstImage, 400, 600) : ''); ?>&description=<?php echo $pName;?>" target="_blank">
                        <i class="icon-pinterest"></i> <span>Pinterest</span>
                    </a>
                    
                </div>
               
            </section>


            <section class="product_details_images">

                <!-- MAIN IMAGE -->
                <section class="product_details_main_image">
                    <?php if($firstImage) : ?>
                        <img src="<?php echo $resourceService->getAssetUrl($firstImage, 900, 1200); ?>" alt="<?php echo $product->name.'- main'; ?>" />
                    <?php else: ?>
                    <?php endif; ?>
                </section>

                <!-- THUMB IMAGES -->
                <section class="product_details_thumbs">
                    <?php $count = 0; foreach ($productImages as $image) : $count++; ?>
                    <a class="thumb-link <?php echo $count == 1 ? 'selected' : ''; ?>" href="" data-largesrc="<?php echo $resourceService->getAssetUrl($image, 900, 1200); ?>" title="<?php echo $product->name.'-'.$count; ?>">
                        <img src="<?php echo $resourceService->getAssetUrl($image, 400, 600); ?>" alt="<?php echo $product->name.'-'.$count; ?>" width="50" height="100" />
                    </a>
                    <?php endforeach; ?>
                </section>


            </section>

            <!-- MAIN -->
            <section class="product_details_content">

                <!-- AVAILABILITY LABEL & PRICE -->
                <section class="product_details_price">
                    <h3><?php echo withCurrency(withFormat($product->price)); ?></h3>
                    <?php if($product->available) : ?>
                        <p><?php echo $resourceService->getLabel("productdetail.availableinstock"); ?></p>
                    <?php else: ?>
                        <p><?php echo $resourceService->getLabel("productdetail.notavailable"); ?></p>
                    <?php endif; ?>
                </section>


                <!-- ADD TO BASKET FORM ONLY IF PRODUCT IS AVAILABLE -->
                <section class="product_details_cart_options">
                    <?php if($product->available || $product->availableToRent) : ?>
                        <div class="row add_to_cart">
                            <form method="GET">
                                <input type="hidden" name="id" value="<?php echo $product->id; ?>" />
                                <input type="hidden" name="action" value="add" />
                                <div>
                                  <label>Quantity</label>
                                  <input type="number" id="pdp-quantity" name="qty" min="1" max="<?php echo $maxItemCount; ?>" value="1" />
                                </div>
                                <div>
                                  <?php $addedForPurchase = CartService::instance()->productInCart($product->id); ?>
                                  <?php $addedForRental = CartService::instance()->productInCart($product->id, 'RENTAL'); ?>

                                  <?php if($product->available): ?>
                                      <?php if($addedForPurchase) : ?>
                                          <button class="addtocart-disabled" type="submit" name="pdp-addtocart" id="pdp-addtocart" disabled="true">
                                              <i class="icon-check"></i> <?php echo $resourceService->getLabel("button.addedtobasket"); ?>
                                          </button>
                                      <?php elseif (!$addedForRental): ?>
                                          <button type="submit" name="pdp-addtocart" id="pdp-addtocart">
                                              <?php echo $resourceService->getLabel("button.addtobasket"); ?>
                                          </button>
                                      <?php endif; ?>
                                  <?php endif; ?>

                                  <?php if($product->availableToRent): ?>
                                    <?php if($addedForRental) : ?>
                                      <button class="addtocart-disabled" type="submit" name="pdp-addtocart" id="pdp-addtocart" disabled="true">
                                        <i class="icon-check"></i> <?php echo $resourceService->getLabel("button.addedforrent"); ?>
                                      </button>
                                      <?php elseif (!$addedForPurchase): ?>
                                      <button type="submit" name="pdp-rent" id="pdp-rent" class="rent">
                                          <?php echo $resourceService->getLabel("button.rent"); ?><br/>
                                        <span><?php echo $resourceService->getLabel("button.rent.subtitle"); ?></span>
                                      </button>
                                    <?php endif; ?>
                                  <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="row additional_buttons">
                        <?php $contactPage = $requestService->getAttribute("contactPage"); ?>
                        <!-- CONTACT BUTTON -->
                        <form action="<?php echo $navigationService->getWebPageUrl($contactPage); ?>" method="GET">
                            <button type="submit" name="pdp-contactus" id="pdp-contactus" class="contact_us">
                                <i class="icon-mail"></i>
                                <?php echo $resourceService->getLabel("button.contactus"); ?>
                            </button>
                        </form>
                        
                        <!-- ADD TO WISHLIST ONLY IF PRODUCT IS AVAILABLE AND CUSTOMER IS LOGGED IN -->
                        <?php if($product->available && $requestService->getAttribute("currentCustomer")) : ?>
                            <form method="GET">
                                <input type="hidden" name="id" value="<?php echo $product->id; ?>" />
                                <input type="hidden" name="wlaction" value="add" />
                                <?php if(WishlistService::instance()->productInWishlist($product->id)) : ?>
                                    <button type="submit" name="pdp-addtowishlist" id="pdp-addtowishlist" class="add_to_wishlist" disabled="true">
                                        <i class="icon-heart"></i> <?php echo $resourceService->getLabel("button.addedtowishlist"); ?>
                                    </button>
                                <?php else : ?>
                                    <button type="submit" name="pdp-addtowishlist" id="pdp-addtowishlist" class="add_to_wishlist">
                                        <i class="icon-heart"></i>
                                        <?php echo $resourceService->getLabel("button.addtowishlist"); ?>
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </section>



                <!-- ACCORDION DATA -->
                <section id="product-main-information">
                    <?php if($product->description1) : ?>
                        <section class="toggle-container">
                            <section class="toggle-handle">
                                <?php echo $resourceService->getLabel("productdetail.description1"); ?>
                            </section>

                            <section class="toggle-content">
                                <?php echo $product->description1; ?>
                            </section>
                        </section>
                    <?php endif; ?>

                    <?php if($product->description2) : ?>
                        <section class="toggle-container">
                            <section class="toggle-handle">
                                <?php echo $resourceService->getLabel("productdetail.description2"); ?>
                            </section>

                            <section class="toggle-content">
                                <?php echo $product->description2; ?>
                            </section>
                        </section>
                    <?php endif; ?>

                    <?php if($product->description3) : ?>
                        <section class="toggle-container">
                            <section class="toggle-handle">
                                <?php echo $resourceService->getLabel("productdetail.description3"); ?>
                            </section>

                            <section class="toggle-content">
                                <?php echo $product->description2; ?>
                            </section>
                        </section>
                    <?php endif; ?>

                    <?php if($deliveryAndReturnContent) : ?>
                        <section class="toggle-container">
                            <section class="toggle-handle">
                                <?php echo $resourceService->getLabel("productdetail.deliveryandreturns"); ?>
                            </section>

                            <section class="toggle-content">
                                <?php echo $deliveryAndReturnContent->content; ?>
                            </section>
                        </section>
                    <?php endif; ?>
                </section>


            </section>

        </section>

    </section>


    <!-- WHAT TO WEAR RELATED ITEMS CONTENT -->
    <section id="product-relatedproducts" class="page_section">

        <!-- HEADING AND SUBHEADING -->
        <h2 class="section_title">
            <?php echo $resourceService->getLabel("productdetail.whattowear.heading"); ?>
            <span><?php echo $resourceService->getLabel("productdetail.whattowear.subheading"); ?></span>
        </h2>


        <div class="product_details_related_content">

            <!-- RELATED PRODUCTS COLLECTION CONTENT -->
            <?php if($collectionContent) : ?>
                <div class="related_content"><?php echo $collectionContent->content; ?></div>
            <?php endif; ?>

            <!-- RELATED PRODUCTS IMAGES -->
            <div class="related_products">
                <?php foreach($relatedProducts as $product) : ?>
                    <?php $relProductUrl = $navigationService->getItemUrl($product, "Product"); ?>
                    <?php $productImage = isset($relatedProductsImages[$product->id]) ? $relatedProductsImages[$product->id][0] : NULL; ?>
                    <?php if($productImage) : ?>
                        <a href="<?php echo $relProductUrl; ?>" title="<?php $product->name; ?>-related" >
                            <img alt="<?php $product->name; ?>-related" src="<?php echo $resourceService->getAssetUrl($productImage, 400, 600); ?>" />
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- COLLECTION RELATED ITEMS CONTENT -->
    <section id="product-collectionrelatedproducts" class="page_section">

        <!-- HEADING AND SUBHEADING -->
        <h2 class="section_title">
            <?php echo $resourceService->getLabel("productdetail.aboutthecollection.heading"); ?>
            <span><?php echo $resourceService->getLabel("productdetail.aboutthecollection.subheading"); ?></span>
        </h2>


        <div class="product_details_related_collection">

            <!-- COLLECTION PRODUCTS COLLECTION CONTENT -->
            <div class="related_content"><?php echo $collection->longDescription; ?></div>

            <!-- COLLECTION PRODUCTS IMAGES RIGHT-->
            <div class="related_products">
                <h3><?php echo $resourceService->getLabel("productdetail.aboutthecollection.title"); ?></h3>

                <div class="related_products_listing">
                    <?php foreach($collectionProducts as $product) : ?>
                        <?php $colProductUrl = $navigationService->getItemUrl($product, "Product"); ?>
                        <?php $productImage = isset($collectionProductsImages[$product->id]) ? $collectionProductsImages[$product->id][0] : NULL; ?>
                        <?php if($productImage) : ?>
                            <a href="<?php echo $colProductUrl; ?>" title="<?php $product->name; ?>-collection" >
                                <img alt="<?php $product->name; ?>-collection" src="<?php echo $resourceService->getAssetUrl($productImage, 400, 600); ?>" />
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- GO TO COLLECTION BUTTON -->
                <a href="<?php echo $navigationService->getItemUrl($collection, "Collection"); ?>" title="<?php echo $collection->name; ?>" class="goto_collection">
                    <?php echo $resourceService->getLabel("productdetail.aboutthecollection.explore"); ?>
                </a>
            </div>

        </div>
    </section>


</section>
