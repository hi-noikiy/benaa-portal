<ul class="nav-links__list">
    <li class="nav-links__item" style="margin-right: 15px;">
        <a href="{{URL::to('/')}}" class="indicator__button">
            <span class="indicator__area" style="height: 73px;">
                <svg width="25px" height="25px">
                    <use xlink:href="{{asset('public/images/sprite.svg#icon-home')}}"></use>
                </svg>
            </span>
        </a>
    </li>
    @php($i = 0)
    @foreach($categories as $cat)
        @if($i < 12)
            <li class="nav-links__item  nav-links__item--has-submenu ">
                <a class="nav-links__item-link" href="{{URL::to('/')}}/product/{{$cat['Id']}}">
                    <div class="nav-links__item-body">
                        {{ucwords(strtolower($cat['Name']))}}
                        <svg class="nav-links__item-arrow" width="9px" height="6px">
                            <use xlink:href="images/sprite.svg#arrow-rounded-down-9x6"></use>
                        </svg>
                    </div>
                </a>
                @php($catimage = $cat['Image_URL__c'])
                @if(isset($cat['Categories__r']))
                <div class="nav-links__submenu nav-links__submenu--type--megamenu nav-links__submenu--size--nl">
                    <div class="megamenu">
                        <div class="megamenu__body">
                            <div class="row">
                                <div class="col-6">
                                    <ul class="megamenu__links megamenu__links--level--0">
                                        <li class="megamenu__item  megamenu__item--with-submenu ">
                                            <a href="{{URL::to('/')}}/product/{{$cat['Id']}}">{{ucwords(strtolower($cat['Name']))}}</a>
                                            <ul class="megamenu__links megamenu__links--level--1">
                                               @php($subcatarr = $cat['Categories__r']['records'])
                                                @foreach($subcatarr as $subcat)
                                                    <li class="megamenu__item"><a href="{{URL::to('/')}}/product/{{strtolower(str_replace(' ', '-', $cat['Name']))}}/{{$subcat['Id']}}/">{{ucwords(strtolower($subcat['Name']))}}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="megamenu__links megamenu__links--level--0">
                                        <li class="megamenu__item  megamenu__item--with-submenu ">

                                            <ul class="megamenu__links megamenu__links--level--1">
                                                <li class="megamenu__item"><img src="{{$catimage}}" width="100%" height="100%"></li>
                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </li>
        @endif
        @php ($i++)
    @endforeach
</ul>
<script src="{{ asset('public/js/header.js') }}"></script>
