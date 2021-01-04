import $ from "jquery";

class Search{
    constructor(){
        // this.openButton = document.querySelector('.js-search-trigger');
        // this.closeButton = document.querySelector('.search-overlay__close');
        // this.searchOverlay = document.querySelector('.search-overlay');
        this.resultsDiv = $(".search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-item");
        this.skeyNumber = 83;
        this.escKeyNumber = 27;
        this.events();
        this.isOverLayOpen = false;
        this.isSpinnerVisible = false;
        this.previousVal;
        this.typingTimer;
    }


    //Call to Events
    events(){
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.searchDB.bind(this));
    }

    //Methods
    searchDB(){
        // console.log('Javascript typing is cool!');
        if(this.previousVal !== this.searchField.val()){
            clearTimeout(this.typingTimer);

            if(this.searchField.val()){
                //Add a loader after typing before showing results
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html("<div class='spinner-loader'></div>");
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this),2000);
            }else{
                this.resultsDiv.html('');
                // this.isSpinnerVisible = false;
            }

            
        }
        this.previousVal = this.searchField.val();
    }

    getResults(){
        // console.log('My belle o');
        // console.log(this.searchField.val());
        let url = "http://localhost:10008/wp-json/wp/v2/posts?search="+ this.searchField.val();
        $.getJSON(url, results => {
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">General Information</h2>
            <ul class="link-list min-list">
            ${results.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
            </ul>`);
        });
        // <li><a href="${results[0].link}">${results[0].title.rendered}</a></li>
        this.isSpinnerVisible = false;
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        // console.log('Open just ran');
        this.isOverLayOpen = true;
    }
    
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        // console.log('Close just ran');
        this.isOverLayOpen = false;
    }

    keyPressDispatcher(e){
        // console.log('Body is shaking'+ e.keyCode);
        if(e.keyCode == this.skeyNumber && !this.isOverLayOpen && !$("input, textarea").is(':focus')){
            this.openOverlay();
        }

        if(e.keyCode == this.escKeyNumber && this.isOverLayOpen){
            this.closeOverlay();
        }
    }
}

export default Search;