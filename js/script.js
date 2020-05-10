window.onload = function() {
	addItemsToCarouselContainer();
};
  
function addItemsToCarouselContainer() {

	const carouselContainer = document.getElementById("carousel-items-container");

	for (item of carouselItemsData) {
		const carouselItems = createCarouselItems(item);
		carouselContainer.innerHTML += carouselItems;
	}
}

function createCarouselItems(item) {
    
    if(item.src === "japanese.jpg"){

        return`
         <div class="carousel-item active">
             <img class="d-block w-100" src="img/home/${item.src}" alt="${item.alt}">
             <div class="carousel-caption d-none d-md-block">
                 <h5>${item.title}</h5>
                 <p>${item.subtitle}</p>
             </div>
         </div>
        `;

    } else {

        return`
            <div class="carousel-item">
                <img class="d-block w-100" src="img/home/${item.src}" alt="${item.alt}">
                <div class="carousel-caption d-none d-md-block">
                    <h5>${item.title}</h5>
                    <p>${item.subtitle}</p>
                </div>
            </div>
        `;
    };
};
  
// JSON DATA

const carouselItemsData = [
    {
      src: 'italian.jpg',
      title: 'Italian cuisine',
      subtitle: 'Great italian cuisine',
      alt: 'Image of italian cuisine'
    },
    {
      src: 'mexican.jpg',
      title: 'Mexican cuisine',
      subtitle: 'Amazing mexican cuisine',
      alt: 'Image of mexican cuisine'
    },
    {
      src: 'japanese.jpg',
      title: 'Japanese cuisine',
      subtitle: 'Traditional japanese cuisine',
      alt: 'Image of japanese cuisine'
    }
  ];
