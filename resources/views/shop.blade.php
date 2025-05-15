@extends('layouts.app')
@section('content') 

<main class="pt-90">
    <section class="shop-main container d-flex pt-4 pt-xl-5">
      <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
        <div class="aside-header d-flex d-lg-none align-items-center">
          <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
          <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
        </div>

        <div class="pt-4 pt-lg-0"></div>

        <div class="accordion" id="categories-list">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-1">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
                Product Categories
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg"></svg>
              </button>
            </h5>
            <div id="accordion-filter-1" class="accordion-collapse collapse show" aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
              <div class="accordion-body p-0 pt-3">
                <form method="GET" action="{{ route('shop.index') }}" id="shopFilterForm">
                  <select name="category" class="form-select mb-2" onchange="document.getElementById('shopFilterForm').submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}" {{ request()->get('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                  </select>

                  <select name="brand" class="form-select mb-2" onchange="document.getElementById('shopFilterForm').submit()">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                      <option value="{{ $brand->id }}" {{ request()->get('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                  </select>

                  <div class="mb-2">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input type="number" name="min_price" id="min_price" class="form-control" placeholder="Min" value="{{ request()->get('min_price') }}">
                  </div>
                  <div class="mb-2">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input type="number" name="max_price" id="max_price" class="form-control" placeholder="Max" value="{{ request()->get('max_price') }}">
                  </div>

                  <select name="sort" class="form-select mb-2" onchange="document.getElementById('shopFilterForm').submit()">
                    <option value="">Default Sorting</option>
                    <option value="price_asc" {{ request()->get('sort') == 'price_asc' ? 'selected' : '' }}>Price, low to high</option>
                    <option value="price_desc" {{ request()->get('sort') == 'price_desc' ? 'selected' : '' }}>Price, high to low</option>
                  </select>

                  <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="shop-list flex-grow-1">
        <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split" data-settings='{
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": 1,
            "effect": "fade",
            "loop": true,
            "pagination": {
              "el": ".slideshow-pagination",
              "type": "bullets",
              "clickable": true
            }
          }'>
          <div class="swiper-wrapper">
            @foreach ($products->where('is_featured', 1) as $product)
            <div class="swiper-slide">
                <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                    <div class="slide-split_text position-relative d-flex align-items-center"
                        style="background-color: #f5e6e0;">
                        <div class="slideshow-text container p-3 p-xl-5">
                            <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                                {{ $product->category->name ?? '' }}<br /><strong>{{ $product->name }}</strong>
                            </h2>
                            <p class="mb-0 animate animate_fade animate_btt animate_delay-5">
                                {{ Str::limit($product->description, 100) }}
                            </p>
                            <a href="{{ $product->slug ? route('shop.product_details', ['product_slug' => $product->slug]) : '#' }}" class="btn btn-primary mt-3">
                                View Details
                            </a>
                        </div>
                    </div>
                    <div class="slide-split_media position-relative">
                        <div class="slideshow-bg" style="background-color: #f5e6e0;">
                            <a href="{{ $product->slug ? route('shop.product_details', ['product_slug' => $product->slug]) : '#' }}">
                                <img loading="lazy"
                                    src="{{ $product->image_name ? asset('uploads/products/' . $product->image_name) : asset('assets/images/products/product_1.jpg') }}"
                                    width="630" height="450"
                                    alt="{{ $product->name }}"
                                    class="slideshow-bg__img object-fit-cover" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
          </div>

          <div class="container p-3 p-xl-5">
            <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-4 pb-xl-2"></div>

          </div>
        </div>

        <div class="mb-3 pb-2 pb-xl-3"></div>

        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
          </div>

          <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            
            <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

            <div class="col-size align-items-center order-1 d-none d-lg-flex">
              <span class="text-uppercase fw-medium me-2">View</span>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="2">2</button>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="3">3</button>
              <button class="btn-link fw-medium js-cols-size" data-target="products-grid" data-cols="4">4</button>
            </div>

            <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
              <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside" data-aside="shopFilter">
                <svg class="d-inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_filter" />
                </svg>
                <span class="text-uppercase fw-medium d-inline-block align-middle">Filter</span>
              </button>
            </div>
          </div>
        </div>

        <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
            @foreach ($products as $product)
            <div class="product-card-wrapper">
                <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper">
                        <a href="{{ $product->slug ? route('shop.product_details', ['product_slug' => $product->slug]) : '#' }}">
                            <img loading="lazy"
                                src="{{ $product->image_name ? asset('uploads/products/' . $product->image_name) : asset('assets/images/products/product_1.jpg') }}"
                                width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                        </a>
                        <form method="post" action="{{ route('cart.add') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image_name }}">
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium js-add-cart"
                                data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                        </form>
                    </div>
                    <div class="pc__info position-relative" href="{{ $product->slug ? route('shop.product_details', ['product_slug' => $product->slug]) : '#' }}">
                        <p class="pc__category">{{ $product->category->name ?? '' }}</p>
                        <h6 class="pc__title"><a href="{{ $product->slug ? route('shop.product_details', ['product_slug' => $product->slug]) : '#' }}">{{ $product->name }}</a></h6>
                        <div class="product-card__price d-flex">
                            <span class="money price">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="product-card__review d-flex align-items-center">
                            <div class="reviews-group d-flex">
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_star" />
                                </svg>
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_star" />
                                </svg>
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_star" />
                                </svg>
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_star" />
                                </svg>
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_star" />
                                </svg>
                            </div>
                            <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
                        </div>
                        <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                            title="Add To Wishlist">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <nav class="shop-pages d-flex justify-content-between mt-3" aria-label="Page navigation">
          <a href="#" class="btn-link d-inline-flex align-items-center">
            <svg class="me-1" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_prev_sm" />
            </svg>
            <span class="fw-medium">PREV</span>
          </a>
          <ul class="pagination mb-0">
            <li class="page-item"><a class="btn-link px-1 mx-2 btn-link_active" href="#">1</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">2</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">3</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">4</a></li>
          </ul>
          <a href="#" class="btn-link d-inline-flex align-items-center">
            <span class="fw-medium me-1">NEXT</span>
            <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_next_sm" />
            </svg>
          </a>
        </nav>
      </div>
    </section>
  </main>

@endsection