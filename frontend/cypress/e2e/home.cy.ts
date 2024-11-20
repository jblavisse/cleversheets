describe('template spec', () => {
  it('passes', () => {
    // Visite la page d'accueil
    cy.visit('http://localhost:3000');

    // Vérifie que le texte "Sheetdeck" est présent
    cy.contains('Sheetdeck').should('be.visible');
  });
});