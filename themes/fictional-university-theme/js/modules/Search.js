import $ from "jquery";

class Search{
    constructor(){
        this.addSearchHTML();
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
            }
        }
        this.previousVal = this.searchField.val();
    }

    getResults(){
        //Get Stuffs from Custom Rest API created
        $.getJSON(universityData.root_url + "/wp-json/university/v1/search?term="+ this.searchField.val(), (results) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information match was found</p>'}
                        ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title} </a>${item.type == 'post' ? `by ${item.author_name}` : ''}</li>`).join('')}
                        ${results.generalInfo.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match was found</p><a href="${universityData.root_url}/programs">View all Programs</a>`}
                        ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title} </a></li>`).join('')}
                        ${results.programs.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match was found</p>`}
                        ${results.professors.map(item => `
                            <li class="professor-card__listitem">
                                <a class="professor-card" href="${item.permalink}">
                                    <img class="professor-card__image" src="${item.image}" alt="${item.title}">
                                    <span class="professor-card__name">${item.title}</span>
                                </a>
                            </li>
                        `).join('')}
                        ${results.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match was found</p><a href="${universityData.root_url}/campuses">View all Campuses</a>`}
                        ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title} </a></li>`).join('')}
                        ${results.campuses.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${results.events.length ? '<ul class="link-list min-list">' : `<p>No Eevnts match was found</p><a href="${universityData.root_url}/events">View all Events</a>`}
                        ${results.events.map(item => `
                            <div class="event-summary">
                                <a class="event-summary__date t-center" href="${item.permalink}">
                                    <span class="event-summary__month"> ${item.month} </span>
                                    <span class="event-summary__day"> ${item.day}</span>
                                </a>
                                <div class="event-summary__content">
                                    <h5 class="event-summary__title headline headline--tiny">
                                        <a href="${item.permalink}">${item.title}</a>
                                    </h5>
                                    <p>
                                        ${item.description}
                                        <a href="${item.permalink}" class="nu gray">Learn more</a>
                                    </p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `);
        });
        //UniversityData object is created by extending PHP WP
        //The Javascript file is enqueued and then localized so as to
        // add objects to the script

        // let url = universityData.root_url + "/wp-json/wp/v2/posts?search="+ this.searchField.val();
        // let url2 = universityData.root_url + "/wp-json/wp/v2/pages?search="+ this.searchField.val();
        
        //Asynchronous API calls
        // $.when($.getJSON(url),$.getJSON(url2))
        // .then((posts, pages) => {
        //     let combined = posts[0].concat(pages[0]);
        //     this.resultsDiv.html(`
        //         <h2 class="search-overlay__section-title">General Information</h2>
        //         ${combined.length ? '<ul class="link-list min-list">' : '<p>No results match was found</p>'}
        //         ${combined.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type== 'posts' ? `by ${item.author_name}` : ''}</li>`).join('')}
        //         ${combined.length ? '</ul>' : ''}
        //     `)
        // },() => {
        //     this.resultsDiv.html('<p>Unexpected Error occured</p>');
        // });
        this.isSpinnerVisible = false;
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 302);
        this.isOverLayOpen = true;
        return false;
    }
    
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverLayOpen = false;
    }

    keyPressDispatcher(e){
        if(e.keyCode == this.skeyNumber && !this.isOverLayOpen && !$("input, textarea").is(':focus')){
            this.openOverlay();
        }

        if(e.keyCode == this.escKeyNumber && this.isOverLayOpen){
            this.closeOverlay();
        }
    }

    addSearchHTML(){
        $("body").append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="Enter what you are looking for?" id="search-item">
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            
            <div class="container">
                <div class="search-overlay__results">
                </div>
            </div>

        </div>
        `);
    }
}
export default Search;