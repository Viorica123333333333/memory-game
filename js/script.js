/* =========================================================
   1. ELEMENT SELECTION
========================================================= */

// Game cards
const cards = document.querySelectorAll(".card");

// Status and feedback elements
const previewMessage = document.getElementById("preview-message");
const statusMessage = document.getElementById("status-message");
const scoreMessage = document.getElementById("score-message");
const scorePopup = document.getElementById("score-popup");

// Sound effect elements
const flipSound = document.getElementById("flip-sound");
const matchSound = document.getElementById("match-sound");
const wrongSound = document.getElementById("wrong-sound");
const winSound = document.getElementById("win-sound");
const gameoverSound = document.getElementById("gameover-sound");

/* =========================================================
   2. GAME STATE VARIABLES
========================================================= */

// Store the currently selected cards
let firstCard = null;
let secondCard = null;

// Track game progress
let wrongAttempts = 0;
let matchedPairs = 0;
let score = 0;

// Prevent interaction while preview is active or cards are being checked
let lockBoard = true;

// Total number of matching pairs in the current game
const totalPairs = cards.length / 2;

// Preview countdown duration in seconds
let previewTime = 5;

/* =========================================================
   3. AUDIO SETTINGS
========================================================= */

// Set softer volume levels for sound effects
flipSound.volume = 0.3;
matchSound.volume = 0.4;
wrongSound.volume = 0.35;
winSound.volume = 0.5;
gameoverSound.volume = 0.5;

/* =========================================================
   4. PREVIEW PHASE
========================================================= */

// Show all cards at the start so the player can memorise them
const previewInterval = setInterval(() => {
  previewTime--;
  previewMessage.textContent = `Memorise the cards: ${previewTime}`;

  if (previewTime === 0) {
    clearInterval(previewInterval);

    // Hide all cards after the preview phase
    cards.forEach((card) => {
      card.querySelector("img").src = "images/back.png";
    });

    previewMessage.textContent = "Start matching!";
    lockBoard = false;
  }
}, 1000);

/* =========================================================
   5. CARD CLICK HANDLING
========================================================= */

// Add click event to every card
cards.forEach((card) => {
  card.addEventListener("click", () => {
    // Stop interaction during preview or while checking cards
    if (lockBoard) {
      return;
    }

    // Prevent selecting the same card twice
    if (card === firstCard) {
      return;
    }

    // Prevent clicking cards that have already been matched
    if (card.classList.contains("matched")) {
      return;
    }

    // Reveal the selected card image
    revealCard(card);

    // Store the first selected card
    if (firstCard === null) {
      firstCard = card;
      return;
    }

    // Store the second selected card and lock the board
    secondCard = card;
    lockBoard = true;

    // Compare the two selected cards
    checkMatch();
  });
});

/* =========================================================
   6. CORE GAME FUNCTIONS
========================================================= */

/**
 * Reveals a card image and plays the flip sound.
 * @param {HTMLElement} card - The selected card element.
 */
function revealCard(card) {
  const image = card.getAttribute("data-image");
  const imgTag = card.querySelector("img");

  imgTag.src = image;

  flipSound.currentTime = 0;
  flipSound.play();
}

/**
 * Checks whether the two selected cards belong to the same pair.
 */
function checkMatch() {
  const firstPair = firstCard.getAttribute("data-pair");
  const secondPair = secondCard.getAttribute("data-pair");

  if (firstPair === secondPair) {
    handleCorrectMatch();
  } else {
    handleWrongMatch();
  }
}

/**
 * Handles the logic for a correct match.
 */
function handleCorrectMatch() {
  // Increase score for a correct match
  score += 10;
  scoreMessage.textContent = `Score: ${score}`;
  showScorePopup("+10", "positive");

  // Play match sound
  matchSound.currentTime = 0;
  matchSound.play();

  // Wait briefly so the player can see the matched cards
  setTimeout(() => {
    firstCard.classList.add("matched");
    secondCard.classList.add("matched");

    matchedPairs++;

    // End the game if all pairs have been found
    if (matchedPairs === totalPairs) {
      winSound.currentTime = 0;
      winSound.play();

      setTimeout(() => {
        alert(`You Win! Final Score: ${score}`);
        location.reload();
      }, 300);
    }

    resetTurn();
  }, 800);
}

/**
 * Handles the logic for an incorrect match.
 */
function handleWrongMatch() {
  // Increase wrong attempt count
  wrongAttempts++;
  statusMessage.textContent = `Wrong attempts: ${wrongAttempts} / 3`;

  // Reduce score, but do not allow it to go below zero
  score = Math.max(0, score - 2);
  scoreMessage.textContent = `Score: ${score}`;
  showScorePopup("-2", "negative");

  // Play wrong attempt sound
  wrongSound.currentTime = 0;
  wrongSound.play();

  // Flip both cards back after a short delay
  setTimeout(() => {
    firstCard.querySelector("img").src = "images/back.png";
    secondCard.querySelector("img").src = "images/back.png";

    // End the game after 3 wrong attempts
    if (wrongAttempts === 3) {
      gameoverSound.currentTime = 0;
      gameoverSound.play();

      setTimeout(() => {
        alert(`Game Over! Final Score: ${score}`);
        location.reload();
      }, 300);
    }

    resetTurn();
  }, 2000);
}

/**
 * Resets the selected cards and unlocks the board for the next turn.
 */
function resetTurn() {
  firstCard = null;
  secondCard = null;
  lockBoard = false;
}

/* =========================================================
   7. SCORE POPUP FEEDBACK
========================================================= */

/**
 * Displays a temporary floating popup showing score changes.
 * @param {string} text - The score change text (e.g. +10 or -2).
 * @param {string} type - The popup style type (positive or negative).
 */
function showScorePopup(text, type) {
  scorePopup.textContent = text;

  // Reset previous popup classes
  scorePopup.className = "";
  scorePopup.classList.add(type);

  // Trigger popup animation
  setTimeout(() => {
    scorePopup.classList.add("show");
  }, 10);

  // Hide popup after a short time
  setTimeout(() => {
    scorePopup.classList.remove("show");
  }, 800);
}
