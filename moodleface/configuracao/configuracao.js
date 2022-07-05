/*   
  INSTRUÇÕES:

  Altere abaixo as propriedades para cada link do MOODLE/LMS:

  "url"           = Link para redirecionamento quando o botão é clicado
  "hidden"        = 'true' inativar o botão. Remover a propriedade ou atribuir 'false' para habilitar.
  "new-tab"       = 'true' para abrir o URL em nova aba. Remover a propriedade ou atribuir 'false' para abrir o URL na mesma aba.

*/

var links_JSON = {
  ".menu-do-topo": {
    ".inicie-o-curso": {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=62",
      "new-tab": true,
    },
    ".apresentacao-do-curso": {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=17",
      "new-tab": true,
    },
    ".guia-de-estudo": {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=10",
      "new-tab": true,
    },
    ".forum-apresentacao": {
      url: "https://cursos.fcee-sc.net.br/mod/forum/view.php?id=11",
      "new-tab": true,
    },
    ".module-4": {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=64",
      "new-tab": true,
    },
  },
  ".modulo-1": {
    apresentacao: {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=13",
      placeholder: "Estudo do Conteúdo",
    },

    atividade: {
      url: "https://cursos.fcee-sc.net.br/mod/quiz/view.php?id=15",
      placeholder: "Atividade Avaliativa",
      "new-tab": true,
    },
  },
  ".modulo-2": {
    apresentacao: {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=22",
      placeholder: "Estudo do Conteúdo",
    },

    atividade: {
      url: "https://cursos.fcee-sc.net.br/mod/quiz/view.php?id=27",
      placeholder: "Atividade Avaliativa",
      "new-tab": true,
    },
  },
  ".modulo-3": {
    apresentacao: {
      url: "https://cursos.fcee-sc.net.br/mod/resource/view.php?id=23",
      placeholder: "Estudo do Conteúdo",
    },

    atividade: {
      url: "https://cursos.fcee-sc.net.br/mod/quiz/view.php?id=26",
      placeholder: "Atividade Avaliativa",
      "new-tab": true,
    },
  }
};
