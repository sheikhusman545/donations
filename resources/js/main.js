import siteContent from "./dynamic-content.js";
import formatDate from "./dateFormatter.js";
import { CountUp } from "./countUp.min.js";

$(document).ready(function () {
console.log(siteContent.ourSponsors);
  toggleMenu();
  populateLogo();
  renderMenu(siteContent.mainMenu, ".main-menu");
  renderMenu(siteContent.secondaryMenu, ".secondary-menu");
  renderPrizes();

  function getTicketData(ticketID) {
    const grandPrize = siteContent.tickets.grandPrize.packageIds.includes(
      ticketID
    )
      ? siteContent.tickets.grandPrize
      : null;

    const ticket = siteContent.tickets.packages.find((p) => p.id === ticketID);

    const entryPluralized = ticket.entry === 1 ? "entry" : "entries";

    return {
      grandPrize,
      ticket,
      entryPluralized,
    };
  }

  function formatNumber(number) {
    if (number >= 1000) {
      return number / 1000 + "k";
    } else {
      return number.toString();
    }
  }

  function formatCurrency(
    amount,
    minimumFractionDigits,
    maximumFractionDigits
  ) {
    amount = parseInt(amount);
    return amount.toLocaleString("en-US", {
      style: "currency",
      currency: "USD",
      minimumFractionDigits: minimumFractionDigits,
      maximumFractionDigits: maximumFractionDigits,
    });
  }

  function renderMenu(menu, targetClass) {
    menu.forEach(function (item) {
      $(targetClass).append(
        $("<li>").append($("<a>", { href: item.link, text: item.text }))
      );
    });
  }

  function populateLogo() {
    const logoTitle = siteContent.logo.text.split(" ");
    const Logo = () => `
      <span class="overlay">
      </span>
      <span><b>${logoTitle[0]}</b> ${logoTitle[1]}</span>
    `;

    $.get(
      siteContent.logo.img,
      function (data) {
        var $svg = $(data).find("svg");

        $svg.find("[fill]").attr("fill", "var(--accent-color)");

        $(".logo span.overlay").append($svg);
      },
      "xml"
    );

    $(".logo").html(Logo);
  }

  // Close menu
  function toggleMenu() {
    const links = $(".menu a");

    links.on("click", function () {
      $("#menu-toggle").prop("checked", false);
    });

    // Mobile menu toggle
    function toggleCheckbox(checkbox) {
      checkbox.prop("checked", !checkbox.prop("checked"));
    }

    const checkboxBtn = $("#menu-toggle");
    const labelBtn = $('label[for="menu-toggle"]');

    labelBtn.on("click", function (e) {
      e.preventDefault();
      toggleCheckbox(checkboxBtn);
      $(".menu").slideToggle();
    });
  }
  // Populate promo content
  $(".promo__title").html(
    `${siteContent.promo.title} <mark>${siteContent.promo.titleAmount}</mark>`
  );
  $(".banner__section h1").html("banner-section");
  $(".promo__subtitle").text(siteContent.promo.subtitle);
  $(".promo__images img").attr("src", siteContent.promo.imageURL);
  $(".promo__content .btn").text(siteContent.promo.button[0].text);
  $(".promo__content .btn").attr("href", siteContent.promo.button[0].link);
  $(".promo__content .promo__link span").text(siteContent.promo.link[0].text);
  $(".promo__content .promo__link").attr(
    "href",
    siteContent.promo.link[0].link
  );
  //populate site banner
  $(".banner-section h1").text(siteContent.bannerSection.mainContent);

  // Populate about section
  $(".about__info .section-title").text(siteContent.about.title);
  $(".about__info .text").text(siteContent.about.description);
  $(".about__info .btn").text(siteContent.about.button[0].text);
  $(".about__info .btn").attr("href", siteContent.about.button[0].link);

  // Populate raffle offer content
  const formattedDate = formatDate(siteContent.raffleOffer.drawingDate);

  const RaffleOffer = () => `
    <div class="raffle-offer__timer overlay">
      <ul class="timer__items">
        <li class="timer__item timer__days">00</li>
        <li class="timer__item timer__hours">00</li>
        <li class="timer__item timer__minutes">00</li>
        <li class="timer__item timer__seconds">00</li>
      </ul>
    </div>
    <div class="raffle-offer__body">
      <p class="raffle-offer__body-title">Drawing on <mark>${formattedDate}</mark></p>
      <div class="raffle-offer__progress">
        <div class="raffle-offer__progress-inner" style="width:${
          siteContent.raffleOffer.progressPercentage
        }%"></div>
      </div>
      <p class="raffle-offer__amount">${formatCurrency(
        siteContent.raffleOffer.amount,0,0
      )}</p>
      <span class="raffle-offer__subtitle">${
        siteContent.raffleOffer.progressPercentage
      }% of ${formatCurrency(siteContent.raffleOffer.targetAmount,0,0)}</span>
    </div>
    <div class="raffle-offer__btn">
      <a href=${siteContent.raffleOffer.button[0].link} class="btn overlay">${
    siteContent.raffleOffer.button[0].text
  }</a>
    </div>
  `;

  $(".raffle-offer").html(RaffleOffer);

  //Raffle Timer
  function raffleTimer() {
    const deadline = siteContent.raffleOffer.drawingDate;
    let timerId = null;

    function pluralize(num, words) {
      return num === 1 ? words[0] : words[1];
    }

    function countdownTimer() {
      const diff = deadline - new Date();
      if (diff <= 0) {
        clearInterval(timerId);
      }
      const days = diff > 0 ? Math.floor(diff / 1000 / 60 / 60 / 24) : 0;
      const hours = diff > 0 ? Math.floor(diff / 1000 / 60 / 60) % 24 : 0;
      const minutes = diff > 0 ? Math.floor(diff / 1000 / 60) % 60 : 0;
      const seconds = diff > 0 ? Math.floor(diff / 1000) % 60 : 0;

      const $days = document.querySelector(".timer__days");
      const $hours = document.querySelector(".timer__hours");
      const $minutes = document.querySelector(".timer__minutes");
      const $seconds = document.querySelector(".timer__seconds");

      if ($days && $hours && $minutes && $seconds) {
        $days.textContent = days < 10 ? "0" + days : days;
        $hours.textContent = hours < 10 ? "0" + hours : hours;
        $minutes.textContent = minutes < 10 ? "0" + minutes : minutes;
        $seconds.textContent = seconds < 10 ? "0" + seconds : seconds;
        $days.dataset.title = pluralize(days, ["Day", "Days"]);
        $hours.dataset.title = pluralize(hours, ["Hour", "Hours"]);
        $minutes.dataset.title = pluralize(minutes, ["Min", "Mins"]);
        $seconds.dataset.title = pluralize(seconds, ["Sec", "Secs"]);
      }
    }

    countdownTimer();

    timerId = setInterval(countdownTimer, 1000);
  }

  raffleTimer();

  //Number Counter
  function startCounters() {
    const Item = ({ id, text }) => `
      <dl>
        <dd id=${id}>0</dd>
        <dt>${text.replace(/\n/g, "<br>")}</dt>
      </dl>
      <span class="divider"></span>
    `;

    $(".about__statistics-list").html(
      siteContent.countersStatistic.map(Item).join("")
    );

    $(".about__statistics-list span:last-child").remove();

    siteContent.countersStatistic.forEach((config) => {
      const counter = new CountUp(config.id, config.value);
      counter.start();
    });
  }

  startCounters();

  // Populate footer address
  const phoneTrimmed = siteContent.footer.phone.replace(/\s/g, "");

  const Address = () => `
    <h2>${siteContent.footer.footerTitle}</h2>
    <address>
      <p>${siteContent.footer.addressLine1}<br>
      ${siteContent.footer.addressLine2}<br>
      ${siteContent.footer.country}
      </p><br>
      <a class="footer__email" href="mailto:${siteContent.footer.email}">${siteContent.footer.email}</a>
      <a href="tel:${phoneTrimmed}">${siteContent.footer.phone}</a>
    </address>
  `;

  $(".footer__address").html(Address);

  siteContent.footer.terms.forEach(function (item) {
    $(".footer__terms").append("<p>" + item + "</p>");
  });

  // Populate tickets section
  $(".tickets .section-title").text(siteContent.tickets.title);

  // Populate ticket packages
  function renderTicketPages() {
    const TicketPackage = ({
      id,
      amount,
      entry,
      grandPrize,
      prizesAmount,
      prizes,
    }) => `
      <label class="option-card" data-id="${id}">
        <input class="option-card__input" type="radio" name="option-card" />
        <div class="option-card__body">
          <span class="option-card__checkbox overlay">
            <svg xmlns="http://www.w3.org/2000/svg" width="17.87" height="12.849" viewBox="0 0 17.87 12.849">
              <g id="Group_2" data-name="Group 2" transform="translate(-436.858 -1650.344)">
                <rect id="Rectangle_31" data-name="Rectangle 31" width="10" height="3" rx="1.5"
                  transform="translate(438.979 1654) rotate(45)" fill="#fff" />
                <rect id="Rectangle_32" data-name="Rectangle 32" width="15" height="3" rx="1.5"
                  transform="translate(442 1660.95) rotate(-45)" fill="#fff" />
              </g>
            </svg>
          </span>
          <div class="d-flex align-items-center justify-content-between">
            <span class="option-card__amount">${formatCurrency(
              amount,
              0,
              0
            )}</span>
            <div class="voucher">
              <span class="entry-number">${entry}</span>
              <span class="entry-label">${
                entry === 1 ? `Entry` : `Entries`
              }</span>
            </div>
          </div>
          <span class="divider"></span>
          <div class="d-flex align-items-center justify-content-between">
            <div class="option-card__grand-prize ${
              grandPrize > 0 ? `blue` : ``
            } ">
              <svg xmlns="http://www.w3.org/2000/svg" width="37.074" height="25.555"
                viewBox="0 0 37.074 25.555">
                <path id="Path_20" data-name="Path 20"
                  d="M13.959,13.892a10.2,10.2,0,0,0,9.249,0c2.552-1.478,2.53-3.878-.03-5.356a10.2,10.2,0,0,0-9.249,0C11.384,10.014,11.4,12.407,13.959,13.892Zm1.173-2.56c.2-.061.389-.13.579-.19a.4.4,0,0,1,.29-.023.259.259,0,0,1,.091.107,2.049,2.049,0,0,0,.61.655.97.97,0,0,0,.145.1,1.774,1.774,0,0,0,.5.19c.5.107.937-.084.884-.389a.7.7,0,0,0-.137-.3,5.506,5.506,0,0,1-.5-.747c-.183-.427-.1-.808.488-1.112a3.024,3.024,0,0,1,2.2-.152c.32.091.32.091.571-.053.084-.046.168-.1.251-.145a.356.356,0,0,1,.45,0c.061.03.114.069.175.1.4.229.4.229.008.465-.282.16-.282.16-.046.35a2.062,2.062,0,0,1,.434.465c.061.091.023.16-.122.206-.221.069-.442.145-.663.213a.407.407,0,0,1-.282.015.39.39,0,0,1-.091-.1,1.848,1.848,0,0,0-.663-.61c-.038-.023-.084-.046-.13-.069a1.668,1.668,0,0,0-.312-.122c-.427-.1-.8.061-.747.328a.9.9,0,0,0,.19.381,5.574,5.574,0,0,1,.427.648c.358.716-.419,1.417-1.661,1.486a3.439,3.439,0,0,1-1.265-.16.481.481,0,0,0-.434.038c-.13.084-.274.16-.4.236a.365.365,0,0,1-.373.008c-.091-.046-.175-.1-.267-.145-.061-.038-.122-.069-.183-.107-.13-.076-.114-.152.008-.229.1-.061.2-.114.29-.175.221-.13.221-.137.038-.282a2.4,2.4,0,0,1-.564-.587C14.843,11.462,14.866,11.424,15.133,11.332Zm8.907,3.032a2.809,2.809,0,0,1,2.56,0,.785.785,0,0,1,.008,1.486,2.809,2.809,0,0,1-2.56,0A.785.785,0,0,1,24.039,14.365Zm-13.5-7.794a2.825,2.825,0,0,1,2.568,0,.785.785,0,0,1,.008,1.486,2.809,2.809,0,0,1-2.56,0C9.838,7.645,9.83,6.982,10.538,6.571ZM.946,9.748,21.159,21.412a4.633,4.633,0,0,0,4.221,0l10.827-6.293a1.294,1.294,0,0,0-.008-2.446L15.986,1a4.673,4.673,0,0,0-4.229,0L.931,7.3A1.294,1.294,0,0,0,.946,9.748ZM5.335,7.271a3.42,3.42,0,0,0-.411-.2l6.469-3.756a2.453,2.453,0,0,0,.343.236,4.721,4.721,0,0,0,4.274,0,1.8,1.8,0,0,0,.35-.251L32.1,12.391a3.437,3.437,0,0,0-.587.267,1.307,1.307,0,0,0,.015,2.476,3.518,3.518,0,0,0,.594.267l-6.529,3.794a2.535,2.535,0,0,0-.465-.343,4.7,4.7,0,0,0-4.274,0,1.958,1.958,0,0,0-.244.168L4.916,9.953a3.165,3.165,0,0,0,.427-.206A1.308,1.308,0,0,0,5.335,7.271ZM.42,12.079a2.06,2.06,0,0,1,1.806.061l21.12,12.19,11.421-6.636a1.97,1.97,0,0,1,1.8,0,.551.551,0,0,1,.008,1.044L24.435,25.793a2.389,2.389,0,0,1-2.156,0l-.427-.244-.3-.175L.375,13.153C-.136,12.848-.128,12.361.42,12.079Z"
                  transform="translate(0 -0.495)" fill="currentColor" />
              </svg>
              <span class="option-card__grand-prize-amount">$${formatNumber(
                grandPrize
              )}</span>
            </div>
            <span class="option-card__plus">+</span>
            <div class="option-card__prizes">
              <svg xmlns="http://www.w3.org/2000/svg" width="19.813" height="20" viewBox="0 0 19.813 20">
                <g id="Group_28" data-name="Group 28" transform="translate(-3.112 -3)">
                  <path id="Path_18" data-name="Path 18"
                    d="M21.456,6.861h-2.15A2.708,2.708,0,0,0,19.269,4.5a2.741,2.741,0,0,0-4.649-.38l-1.6,2.178-1.6-2.176a2.741,2.741,0,0,0-4.648.375,2.711,2.711,0,0,0-.038,2.369H4.574A1.466,1.466,0,0,0,3.112,8.33v1.56a1.459,1.459,0,0,0,1.461,1.461H21.456a1.466,1.466,0,0,0,1.469-1.461V8.33A1.473,1.473,0,0,0,21.456,6.861Zm-5.5-1.768a1.089,1.089,0,0,1,1.846.151,1.094,1.094,0,0,1-.959,1.591l-2.18.015ZM8.239,5.24a1.088,1.088,0,0,1,1.845-.146L11.377,6.85,9.2,6.835A1.093,1.093,0,0,1,8.239,5.24Z"
                    fill="var(--accent-color)" />
                  <path id="Path_19" data-name="Path 19"
                    d="M4.787,22.582A2.424,2.424,0,0,0,7.214,25H19.382a2.424,2.424,0,0,0,2.427-2.419V15H4.787Z"
                    transform="translate(-0.279 -2.001)" fill="var(--accent-color)" />
                </g>
              </svg>
              <p class="option-card__prizes-desc">${
                prizes === prizesAmount
                  ? `All ${prizesAmount} Prizes`
                  : `${prizes}<span>/${prizesAmount}</span> Prizes`
              }</p>
            </div>
          </div>
        </div>
      </label>
    `;

    $(".options-grid").html(
      siteContent.tickets.packages.map(TicketPackage).join("")
    );
  }

  renderTicketPages();

  //Select the nearest card amount rounding down
  $("#customAmount").on("blur", function () {
    let inputVal = $(this).val();
    let formattedValue = parseInt(inputVal).toLocaleString("en-US", {
      minimumFractionDigits: 2,
    });
    if (isNaN(parseFloat(formattedValue))) {
      formattedValue = "";
    }
    $(this).val(formattedValue);
  });

  $("#customAmount").on("focus", function () {
    let formattedValue = $(this).val().replace(/,/g, "");

    if (formattedValue.includes(".00")) {
      formattedValue = formattedValue.replace(".00", "");
    }
    $(this).val(formattedValue);
  });

  $("#customAmount").on("input", function () {
    var timeout;
    clearTimeout(timeout);

    var inputValue = $(this).val().replace(/\D/g, "");

    if (parseInt(inputValue) <= 0) {
      inputValue = "";
    }

    $(this).val(inputValue);

    var cards = $(".options-grid .option-card");
    var selectedCard = null;
    var closestDifference = Infinity;

    cards.find(".option-card__input").prop("checked", false);
    $(".prizes-grid .prize").removeClass("selected");
    $(".cart, .cart-mobile").slideUp();

    timeout = setTimeout(function () {
      cards.each(function () {
        var card = $(this);
        var cardNumberValue = card.find(".option-card__amount").text();
        var cardNumber = parseInt(cardNumberValue.replace(/[\$,]/g, ""));
        var difference = inputValue - cardNumber;

        if (difference >= 0 && difference < closestDifference) {
          closestDifference = difference;
          selectedCard = card;
        }
      });

      if (selectedCard) {
        const ticketId = selectedCard.data("id");

        const { grandPrize, ticket, entryPluralized } = getTicketData(ticketId);

        const titleString = grandPrize
          ? `Donate <mark>$${inputValue}</mark> for <mark>${
              ticket.entry
            } ${entryPluralized}</mark> to win the <mark>${formatNumber(
              grandPrize.amount
            )} grand prize</mark> and <mark>${
              ticket.prizes
            } additional prizes</mark>.`
          : `Donate <mark>$${inputValue}</mark> for <mark>${ticket.entry} ${entryPluralized}</mark> to win the <mark>${ticket.prizes} prizes</mark>.`;

        handleEvent(selectedCard, "click");

        $(".ticket-options__heading-title").html(titleString);

        $(".cart, .cart-mobile").slideDown();
      } else if (closestDifference && inputValue) {
        const lowestTicketAmount = Math.min.apply(
          null,
          siteContent.tickets.packages.map(function (p) {
            return p.amount;
          })
        );

        $(".prizes__subtitle").html(
          "Select a ticket<br> to see the included prizes"
        );

        $(".ticket-options__heading-title").html(
          `${formatCurrency(
            inputValue
          )} will help us significantly. To <mark>qualify for the raffle</mark>, please consider <mark>upgrading to an $${lowestTicketAmount} contribution</mark>. Your support is greatly valued.
          `
        );
      } else {
        $(".ticket-options__heading-title").html(
          `Enter <mark>any amount</mark> and we'll match you with the
          closest ticket benefits automatically`
        );
        $(".cart, .cart-mobile").slideUp();
      }
    }, 1000);
  });

  // Populate prizes
  function renderPrizes() {
    const numberWithComma = (x) => {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    const Prize = ({ number, imageURL, title }) => `
    <div class="prize" data-number="${number}">
      <div class="prize__body">
        <span class="option-card__checkbox overlay">
          <svg xmlns="http://www.w3.org/2000/svg" width="17.87" height="12.849" viewBox="0 0 17.87 12.849">
            <g id="Group_2" data-name="Group 2" transform="translate(-436.858 -1650.344)">
              <rect id="Rectangle_31" data-name="Rectangle 31" width="10" height="3" rx="1.5"
                transform="translate(438.979 1654) rotate(45)" fill="#fff" />
              <rect id="Rectangle_32" data-name="Rectangle 32" width="15" height="3" rx="1.5"
                transform="translate(442 1660.95) rotate(-45)" fill="#fff" />
            </g>
          </svg>
        </span>
        <div class="prize__image">
          <img src="${imageURL}" alt="">
        </div>
        <div class="prize__desc">
          <div class="d-flex align-items-center">
            <span class="prize__number overlay">${number}</span>
            <p class="prize__name">${title}</p>
          </div>
          <div>
            <button class="btn">Details</button>
          </div>
        </div>
      </div>
    </div>
  `;

    const GrandPrize = () => `
    <div class="prize grand-prize" data-number="${
      siteContent.tickets.grandPrize.number
    }">
      <div class="prize__body">
        <span class="option-card__checkbox overlay">
          <svg xmlns="http://www.w3.org/2000/svg" width="17.87" height="12.849" viewBox="0 0 17.87 12.849">
            <g id="Group_2" data-name="Group 2" transform="translate(-436.858 -1650.344)">
              <rect id="Rectangle_31" data-name="Rectangle 31" width="10" height="3" rx="1.5"
                transform="translate(438.979 1654) rotate(45)" fill="#fff" />
              <rect id="Rectangle_32" data-name="Rectangle 32" width="15" height="3" rx="1.5"
                transform="translate(442 1660.95) rotate(-45)" fill="#fff" />
            </g>
          </svg>
        </span>
        <div class="grand-prize__img">
          <img src="${siteContent.tickets.grandPrize.imageURL}" alt="">
          <div class="position-relative z-1">
            <p class="grand-prize__amount">$${numberWithComma(
              siteContent.tickets.grandPrize.amount
            )}</p>
            <span class="grand-prize__subtitle">Grand Prize</span>
            <p class="grand-prize__desc">${
              siteContent.tickets.grandPrize.description
            }</p>
            <button class="btn">Details</button>
          </div>
        </div>
      </div>
    </div>
  `;

    $(".prizes-grid").html(
      `${GrandPrize()} ${siteContent.tickets.prizes.map(Prize).join("")}`
    );
  }

  let ticketAmount = null;

  $("#collapseCart").on("show.bs.collapse hide.bs.collapse", function (e) {
    if (e.type === "show") {
      $(".cart__summary .checkout-btn").text("Back");
    } else {
      $(".cart__summary .checkout-btn").html(`<span>Checkout</span>
      <span class="divider"></span>
      <span class="checkout-btn__amount">${formatCurrency(
        ticketAmount
      )}</span>`);
    }
  });

  $(".options-grid").on("click mouseenter", ".option-card", function (e) {
    if ($(e.target).is("input")) {
      return;
    }

    if (e.type === "click") {
      $("#customAmount").val("");
    }

    handleEvent(this, e.type);
  });

  $(".options-grid .option-card").on("mouseleave", function () {
    $(".prizes-grid .prize").removeClass("highlighted");
  });

  function handleEvent(el, type) {
    const ticketId = parseInt($(el).data("id"));
    const className = type === "click" ? "selected" : "highlighted";

    const { grandPrize, ticket, entryPluralized } = getTicketData(ticketId);

    const activePrizes = siteContent.tickets.prizes.filter((prize) =>
      prize.packageIds.includes(ticketId)
    );

    ticketAmount =
      $("#customAmount").val() === ""
        ? ticket.amount
        : $("#customAmount").val();

    $(".prizes-grid .prize").removeClass(className);

    if (grandPrize) {
      $(".prizes-grid .grand-prize").addClass(className);
    } else {
      $(".prizes-grid .grand-prize").removeClass(className);
    }

    activePrizes.forEach((p) => {
      $(`.prizes-grid .prize[data-number='${p.number}']`).addClass(className);
    });

    if (type === "click") {
      $(".prizes__subtitle").html(
        `Your donation includes <b>${ticket.entry} ${entryPluralized}</b><br>
        into the below <b>${activePrizes.length} selected prizes</b>`
      );

      $(".ticket-options__heading-title").html(
        `Enter <mark>any amount</mark> and we'll match you with the
        closest ticket benefits automatically`
      );

      $(".cart__subtitle, .entry-quantity").text(
        `${ticket.entry} ${entryPluralized}`
      );
      $(".prizes-quantity").text(`${ticket.prizes} Prizes`);

      $(".selected-tickets .cart__summary-list").empty();

      activePrizes.forEach((p) => {
        $(".selected-tickets .cart__summary-list").append(
          $("<li>").text(p.title)
        );
      });

      if (grandPrize) {
        $(".selected-tickets .cart__summary-list").prepend(
          $("<li>").text(`$${formatNumber(grandPrize.amount)} Grand Prize`)
        );
      }

      $(".checkout-btn__amount").html(`${formatCurrency(ticketAmount)}`);
      $(
        ".cart .selected-tickets .cart__title, .cart-mobile .summary-amount"
      ).html(
        `${siteContent.cart.ticketsTitle} <span>${formatCurrency(
          ticketAmount
        )}</span>`
      );

      $(".cart, .cart-mobile").slideDown();
    }
  }
  // Populate cart
  $(".cart .cart__heading, .cart-mobile .cart__heading").text(
    siteContent.cart.heading
  );
  $(".cart .selected-gifts .cart__title").text(siteContent.cart.giftsTitle);
});
