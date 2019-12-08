import {Flipper, spring} from 'flip-toolkit';
/**
 *
 */
export default class Filter {
    /**
     *
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return
        }


        this.pagination = document.querySelector('.js-filter-pagination');
        this.content = document.querySelector('.js-filter-content');
        this.sorting = document.querySelector('.js-filter-sorting');
        this.form = document.querySelector('.js-filter-form');
        this.page = parseInt(new URLSearchParams(window.location.search).get('page') || 1);
        this.moreNav = this.page === 1;
        this.bindEvents();


    }

    bindEvents() {

        const aClickListener = e => {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'))
            }
        };
        this.sorting.addEventListener('click', e => {
            aClickListener(e);
            this.page = 1;
        });
        if(this.moreNav) {
            this.pagination.innerHTML = '<button class="btn btn-primary"> Voir plus </button>'
            this.pagination.querySelector('button').addEventListener('click', this.loadMore.bind(this))
        } else {
            this.pagination.addEventListener('click',aClickListener);
        }
        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', e => {
                input.addEventListener('change', this.loadForm.bind(this));
            })
        })
    }

    async loadMore() {
        const button = this.pagination.querySelector('button');
        button.setAttribute('disabled','disabled');
        this.page++;
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        params.set('page',this.page);
        await this.loadUrl(url.pathname + '?' + params.toString(),true);

        button.removeAttribute('disabled')


    }

    async loadForm() {
        this.page = 1;
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') || window.location.href);
        const params = new URLSearchParams();
        data.forEach((value, key) => {
            params.append(key, value)
        });
        return this.loadUrl(url.pathname + '?' + params.toString())
    }

    async loadUrl(url,append = false) {
        this.showLoader();
        const params = new URLSearchParams(url.split('?')[1] || '');
        params.set('ajax',1);
        const response = await fetch(url.split('?')[0] + '?' + params.toString(),{
            headers : {
                'X-Requested-With' : 'XMLHttpRequest'
            }
        });

        if(response.status >= 200 && response.status < 300 ) {
            const data = await response.json();
            this.flipContent(data.content,append);
            this.sorting.innerHTML = data.sorting;
             if(!this.moreNav) {
                 this.pagination.innerHTML = data.pagination;
             } else if(this.page === data.pages) {
                 this.pagination.style.display = 'none'
             } else {
                 this.pagination.style.display = 'block'
             }
             this.updatePrix(data);
            params.delete('ajax');
            history.replaceState({},'',url.split('?')[0] + '?' + params.toString());
        } else {
            console.log(response)
        }
        this.hideLoader();

    }

    flipContent (content , append) {
        const flipMethod = 'gentle';
        const exitSpring = function(element,index,onComplete) {
                spring({
                    config: 'stiff',
                    values: {
                        translateY: [0, -20],
                        opacity: [1,0 ]
                    },
                    onUpdate: ({translateY, opacity}) => {
                        element.style.opacity = opacity;
                        element.style.transform = `translateY(${translateY}px)`;
                    },
                    onComplete
                })
        };
        const appearSpring = function(element,index) {
            spring({
                config: 'stiff',
                values: {
                    translateY: [20, 0],
                    opacity: [0,1]
                },
                onUpdate: ({translateY, opacity}) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                },
                delay : index * 20
            })
        };


        const flipper = new Flipper({
            element: this.content
        });
        this.content.children.forEach(element => {
            flipper.addFlipped({
                element,
                spring : flipMethod,
                flipId: element.id,
                shouldFlip: false,
                onExit: exitSpring
            })
        });
        flipper.recordBeforeUpdate();
        if(append) {
            this.content.innerHTML += content;
        } else {
            this.content.innerHTML = content;
        }
         this.content.children.forEach(element => {
            flipper.addFlipped({
                element,
                spring : flipMethod,
                flipId: element.id,
                onAppear : appearSpring
            })
        });
        flipper.update();
    }


    showLoader() {
        this.form.classList.add('is-loading');
        const loader = this.form.querySelector('.js-loading');

        if(loader === null) {
            return
        }

        loader.setAttribute('aria-hidden', 'false');
        loader.style.display = null;
    }

    hideLoader() {
        this.form.classList.remove('is-loading');
        const loader = this.form.querySelector('.js-loading');

        if(loader === null) {
            return
        }

        loader.setAttribute('aria-hidden', 'true');
        loader.style.display = 'none';
    }

    updatePrix({min,max}){
        const slider = document.getElementById('prices-slider');
        if(slider === null) {
            return
        }

        slider.noUiSlider.updateOptions({
            range: {
                min: [min],
                max: [max]
            }}
            )
        const minValue = document.getElementById('min');
        const maxValue = document.getElementById('max');
        minValue.setAttribute('value', min);
        maxValue.setAttribute('value',max)


    }


}