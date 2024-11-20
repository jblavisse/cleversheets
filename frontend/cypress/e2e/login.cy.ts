describe('template spec', () => {
  it('passes', () => {
    // Visite de la page de connexion
    cy.visit('http://localhost:3000/login');

    // Vérifie que le texte "Login" est présent
    cy.contains('Login').should('be.visible');

    // Remplit le formulaire de connexion
    cy.get('input[type="email"]').type('user@exemple.com');
    cy.get('input[type="password"]').type('$aA123456789');

    // Soumet le formulaire de connexion
    cy.get('button[type="submit"]').click();

    // Vérifie que le texte "Dashboard" est présent
    cy.contains('Dashboard').should('be.visible');
  });
});