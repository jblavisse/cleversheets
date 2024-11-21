describe('template spec', () => {
  it('passes', () => {
    // Visite de la page de connexion
    cy.visit('http://localhost:3000/login');

    // Vérifie que le texte "Login" est présent
    cy.contains('Login').should('be.visible');

    // Attend que le formulaire de connexion soit présent
    cy.wait(150);

    // Remplit le formulaire de connexion
    cy.get('input[type="email"]').type('user@exemple.com', { delay: 100 });
    cy.get('input[type="password"]').type('$aA123456789', { delay: 100 });

    // Soumet le formulaire de connexion
    cy.get('button[type="submit"]').click();

    // Vérifie que le texte "Dashboard" est présent
    cy.contains('Dashboard').should('be.visible');

    // Visite la page de création de catégorie
    cy.visit('http://localhost:3000/categories/create');

    // Vérifie que le texte "Create Category" est présent
    cy.contains('Create Category').should('be.visible');

    // Remplit le formulaire de création de catégorie
    cy.get('#categoryName').type('NameCategory', { delay: 100 });

    // Soumet le formulaire de création de catégorie
    cy.get('button[type="submit"]').click();

    // Véfifie que la notification de succès est présente
    // cy.contains('Cheatsheet enregistrée avec succès !').should('be.visible');
  });
});