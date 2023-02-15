<div class="col-2 left-side shadow" style="padding: 0">
  <nav class="navbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#"
          >offer Pakage
          <i class="bi bi-arrow-right"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=""
          >Your Favorite Products
          <i class="bi bi-arrow-right"></i>
        </a>
      </li>
    </ul>
    <div class="m-auto">
      <h6>Our Products Catagory</h6>
    </div>
  </nav>
  
  <div class="left-bottom-div bg-light">
    <div class="navbar left-bottom-nav bg-light">
          @php
            $category =\App\Models\Category::all();
        @endphp
      <ul class="navbar-nav">
        @forelse($category as $cat)
        <li class="nav-item">
          @if($cat->sub_category->count()>0)
            <a
              class="nav-link dropdown-toggle categorybutton cat{{$cat->id}}" href="{{ route('category.list',[$cat->id]) }}"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                class="side-nav-icon"
                src="{{ asset('./../POS/uploads/category') }}/{{ $cat->image }}"
                alt=""
              />
              {{ $cat->category_name }}
            </a>
            

            <ul class="dropdown-menu  py-0">
              @foreach ($cat->sub_category as $subcat)
                <li>
                  @if($subcat->child_category->count()>0)
                    <a class="dropdown-item dropdown-toggle subcategorybutton subcat{{$subcat->id}}" href="{{ route('category.subcategory.list',[$cat->id,$subcat->id]) }}"
                      data-bs-toggle="dropdown" aria-expanded="false"
                      ><img
                        class="side-nav-icon"
                        src="{{ asset('./../POS/uploads/category') }}/{{ $subcat->image }}"
                        alt=""
                      />{{ $subcat->subcategory_name }}
                    </a>
                  
                    <ul class="dropdown-menu py-0">
                      @foreach ($subcat->child_category as $chcat)
                      <li>
                          <a class="dropdown-item childcategorybutton chcat{{$chcat->id}}" href="{{ route('category.product.list',[$cat->id,$subcat->id,$chcat->id]) }}"
                            ><img
                              class="side-nav-icon"
                              src="{{ asset('./../POS/uploads/category') }}/{{ $chcat->image }}"
                              alt=""
                            />{{ $chcat->childcategory_name }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  @else
                  <a class="dropdown-item dropdown-toggle subcategorybutton subcat{{$subcat->id}}" href="{{ route('category.product.list',[$cat->id,$subcat->id]) }}"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    ><img
                      class="side-nav-icon"
                      src="{{ asset('./../POS/uploads/category') }}/{{ $subcat->image }}"
                      alt=""
                    />{{ $subcat->subcategory_name }}
                  </a>
                  @endif
                </li>
              @endforeach
            </ul>
          @else
            <a
              class="nav-link dropdown-toggle categorybutton cat{{$cat->id}}" href="{{ route('category.product.list',[$cat->id]) }}"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                class="side-nav-icon"
                src="{{ asset('./../POS/uploads/category') }}/{{ $cat->image }}"
                alt=""
              />
              {{ $cat->category_name }}
            </a>
          @endif
        </li>
        @empty
        @endforelse
      </ul>
    </div>
  </div>
</div>
@push('scripts')
<script>
  $('.categorybutton').each(function(e){
      if($(this).hasClass('cat{{isset(request()->route()->parameters["category_id"])?request()->route()->parameters["category_id"]:''}}')){
          $(this).addClass("show");
          $(this).next('ul').addClass("show");
      }
  })
  $('.subcategorybutton').each(function(e){
      if($(this).hasClass('subcat{{isset(request()->route()->parameters["subcategory_id"])?request()->route()->parameters["subcategory_id"]:''}}')){
          $(this).addClass("show");
          $(this).next('ul').addClass("show");
      }
  })
  $('.childcategorybutton').each(function(e){
      if($(this).hasClass('chcat{{isset(request()->route()->parameters["childcategory_id"])?request()->route()->parameters["childcategory_id"]:''}}')){
          $(this).addClass("show");
      }
  })
  
  
  $('.categorybutton').click(function(){
      window.location=$(this).attr('href');
  })
  $('.subcategorybutton').click(function(){
      window.location=$(this).attr('href');
  })
</script>

@endpush