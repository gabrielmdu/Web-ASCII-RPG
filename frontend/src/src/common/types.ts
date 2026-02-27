export interface User {
  name: string;
  email: string;
  isVerified: boolean;
  activeSessions?: GameSession[];
}

export interface Game {
  id: number;
  creator?: User;
  name: string;
  slug: string;
  isPublic: boolean;
  description: string;
  version: string;
  lastModified: string;
  settings: Record<string, unknown> | null;
  sessions?: GameSession[];
  scenesCount?: number;
  scenesUrl: string;
}

export interface GameSession {
  id: number;
  player?: User;
  game?: Game;
  status: GameSessionStatus;
  currentScene?: unknown;
  settings: Record<string, unknown> | null;
  history: Record<string, unknown> | null;
  createdAt: string;
  updatedAt: string;
}

enum GameSessionStatus {
  ACTIVE = 'active',
  FINISHED = 'finished',
  ABANDONED = 'abandoned',
}
